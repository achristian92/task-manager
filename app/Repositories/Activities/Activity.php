<?php

namespace App\Repositories\Activities;

use App\Mail\SendEmailActivitiesDeadline;
use App\Repositories\ActivityHistories\ActivityHistory;
use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\PartialActivities\PartialActivity;
use App\Repositories\SubActivities\SubActivity;
use App\Repositories\Tags\Tag;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Activity extends Model
{
    protected $guarded = ['id'];

    CONST TYPE_PLANNED     = 'planned';
    CONST TYPE_APPROVED    = 'approved';
    CONST TYPE_EVALUATION  = 'evaluation';
    CONST TYPE_COMPLETED   = 'completed';
    CONST TYPE_PARTIAL     = 'partial';
    CONST TYPE_OVERDUE     = 'overdue';


    public static $statuses = array(
        'planned',
        'approved',
        'evaluation',
        'completed',
        'partial',
        'overdue',
    ); //new

    public const TYPE_STATE = [
        self::TYPE_PLANNED     => 'Planeado',
        self::TYPE_APPROVED    => 'Aprobado',
        self::TYPE_EVALUATION  => 'Evaluación',
        self::TYPE_COMPLETED   => 'Completado',
        self::TYPE_PARTIAL     => 'Parcial',
        self::TYPE_OVERDUE     => 'Vencido'
    ];

    public static function getStatusesList()
    {
        $list = [];
        foreach (self::$statuses as $status) {
            $list[$status] = l($status, [], 'appmultilang');
        }

        return $list;
    }

    public function statusName():string
    {
        if ($this->isOverDue()) return self::TYPE_STATE[self::TYPE_OVERDUE];
        return $this->isPlannedState() ? self::TYPE_STATE[self::TYPE_PLANNED] :
            (($this->isApprovedState()) ? self::TYPE_STATE[self::TYPE_APPROVED] :
                (($this->isPartialCompletedState() ? self::TYPE_STATE[self::TYPE_PARTIAL] : self::TYPE_STATE[self::TYPE_COMPLETED])));
    }

    public function isPartialComplete(): bool
    {
        return $this->is_partial;
    }

    public function isPlannedState():bool
    {
        return $this->status === self::TYPE_PLANNED;
    }

    public function nameUserStateActivity(): string
    {
        if ($this->isApprovedState()) {
            return $this->approved_by->name;
        }

        return $this->created_by->name;
    }

    public function isApprovedState():bool
    {
        return $this->status === self::TYPE_APPROVED;
    }

    public function currentStatus()
    {
        if ($this->isOverDue()) return self::TYPE_OVERDUE;
        return $this->status;
    }

    public function isOverDue(): bool
    {
        if ($this->isPartialCompletedState()) return false;
        return !$this->isCompletedState() && $this->due_date < Carbon::now()->format('Y-m-d');
    }

    public function isPartialCompletedState():bool
    {
        return $this->status === self::TYPE_PARTIAL;
    }
    public function isCompletedState():bool
    {
        return $this->status === self::TYPE_COMPLETED;
    }

    public function estimatedTime():string
    {
        return $this->isPlanned() ? $this->time_estimate : "00:00";
    }

    public function currentRecordedTime(): string
    {
        return empty($this->time_real) ? "00:00" : $this->time_real;
    }

    public function totalTimeEntered(Collection $subActivities, Collection $partialActivities)
    {
        $realTime = empty($this->time_real) ? "00:00" : $this->time_real;

        if ($subActivities->isEmpty() && $partialActivities->isEmpty()) {
            return $realTime;
        }

        if ($subActivities->isNotEmpty()) {
            $realTime = sumArraysTime([$realTime,sumTime($subActivities,'duration')]);
        }

        if ($partialActivities->isNotEmpty()) {
            $realTime = sumArraysTime([$realTime,sumTime($partialActivities,'duration')]);
        }

        return $realTime;
    }
    public function isCompletedOutOfDate(): bool
    {
        if (! $this->isCompletedState()) {
            return false;
        }

        if (Carbon::parse($this->due_date)->format('Y-m-d') === Carbon::parse($this->completed_date)->format('Y-m-d')) {
            return false;
        }

        return true;
    }
    public function dependenceIDS(): array
    {
        if (!self::hasDependence()) return [];

        return collect(explode(",", $this->previous_id))->transform( function ($previous) {
            return intval($previous);
        })->toArray();
    }
    public function hasDependence(): bool
    {
        if ($this->previous_id === '' || is_null($this->previous_id))
            return false;

        return true;
    }

    public function isOwnerDifferent(): bool
    {
        return $this->user_id !== $this->created_by_id;
    }

    public function isPartialOrHasSubActivities(): bool
    {
        return $this->isPartialComplete() || $this->with_subactivities;
    }

    public function reassign(Request $request): bool
    {
        if (! $this->isPartialOrHasSubActivities()) {

            if (!$this->notified)
                self::notifyAssignment();

            return $this->update([ 'is_reassign' => 1, 'notified' => 1 ]);

        }

        $newActivity                      = $this->replicate();
        $newActivity->user_id             = $request->user_id;
        $newActivity->is_reassign         = 1;
        $newActivity->is_partial          = 0;
        $newActivity->with_subactivities  = 0;
        $newActivity->notified            = 1;
        $newActivity->push();

        $newActivity->notifyAssignment();


        $newActivity->histories()->create([
            'user'          => Auth::user()->full_name,
            'description'   => "Actividad($this->id) reasignada a " .$newActivity->user->name,
            'registered_at' => Carbon::now()
        ]);

        History(UserHistory::STORE,"Replico la actividad  $newActivity->name",$newActivity);

        //CURRENT UPDATE
        return $this->update([
            'user_id'        => $this->created_by_id,
            'status'         => self::TYPE_COMPLETED,
            'completed_date' => now(),
        ]);
    }


    public function isPlanned(): bool
    {
        return $this->is_planned;
    }
    public function canCompleted(): bool
    {
        if ((self::isApprovedState() || self::isPartialCompletedState()) && self::isPlanned() && self::isDependenciesCompleted())
            return true;

        return false;
    }
    public function canUpdate(): bool
    {
        if ($this->isPlannedState()) return true;

        if (Auth::user()->isAdminOrSupervisor())
            if (! $this->isCompletedState() && $this->start_date >= date('Y-m-d') )
                return true;

        return false;
    }
    public function canApprove(): bool
    {
        if (Auth::user()->isAdminOrSupervisor() && $this->isPlannedState())
            return true;

        return false;
    }
    public function canReverse(): bool
    {
        if ($this->isApprovedState())
            return true;

        return false;
    }
    public function canDestroy(): bool
    {
        if (!self::isPlanned() || self::isPlannedState())
            return true;

        if (Auth::user()->isAdminOrSupervisor())
            if (!$this->isCompletedState() && $this->start_date >= date('Y-m-d') )
                return true;

        return false;
    }

    public function isDependenciesCompleted(): bool
    {
        if (!self::hasDependence())
            return true;

        $array_value = [];
        foreach (self::dependenceIDS() as $act_id) {
            $activity = Activity::find($act_id);
            array_push($array_value,$activity->isCompletedState() || $activity->isPartialCompletedState());
        }

        return !in_array(false,$array_value);

    }

    public function notifyAssignment()
    {
        $company = $this->user->company;

        if ($company->notifyAssignmentActivity()) {
            history(UserHistory::NOTIFY,"Notificó la asignación de la actividad  $this->name",$this);
            Mail::to($this->user->email)
                ->send(new SendEmailActivitiesDeadline($this));
        }


    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function created_by()
    {
        return $this->belongsTo(User::class,'created_by_id');
    }
    public function approved_by()
    {
        return $this->belongsTo(User::class,'created_by_id');
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class)->withDefault([
            'id'    => '',
            'name'  => 'Sin etiquetas',
            'color' => '#dde5e8'
        ]);
    }
    public function partials()
    {
        return $this->hasMany(PartialActivity::class)->orderBy('completed_at');
    }
    public function sub_activities()
    {
        return $this->hasMany(SubActivity::class)->orderBy('completed_at');
    }

    public function histories()
    {
        return $this->hasMany(ActivityHistory::class)->orderBy('registered_at');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if (Auth::check()) {
                $record->created_by_id = Auth::id();
                $record->created_date  = Carbon::now();
                $record->company_id    = companyID();
            }
        });
    }

}
