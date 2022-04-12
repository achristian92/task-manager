<?php

namespace App\Repositories\Activities\Repository;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Histories\UserHistory;
use App\Repositories\PartialActivities\PartialActivity;
use App\Repositories\SubActivities\SubActivity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;

class ActivityRepo extends BaseRepository implements IActivity
{
    use ActivityTrait, ActivityTransformable;

    public function model()
    {
        return Activity::class;
    }

    public function createActivity(array $params): Activity
    {
        $createdBy = [
            'is_assign'     => $params['user_id'] !== Auth::id(),
        ];

        $activity = $this->model->create($params + $createdBy);

        $this->saveHistory($activity,'Actividad creada');
        history(UserHistory::STORE,"Actividad creada $activity->name",$activity);

        return $activity;
    }

    public function updateActivity(array $params, int $id): bool
    {
        $updatedBy = [
            'updated_by_id' => Auth::id(),
            'updated_date'  => now(),
        ];

        $activityUpdate = $this->model::find($id);
        $activityUpdate->update($params + $updatedBy);

        $activity =  $this->model::find($id);

        $this->saveHistory($activity, "Actividad actualizada");
        history(UserHistory::UPDATED,"Actividad actualizada $activity->name",$activity);

        return true;
    }

    public function deleteActivity(int $id): bool
    {
        $activity = $this->model::find($id);
        $activity->histories()->delete();
        $activity->sub_activities()->delete();
        $activity->partials()->delete();
        history(UserHistory::DELETE,"Eliminó la actividad $activity->name",$activity);
        return $activity->delete();
    }

    public function saveHistory(Activity $activity, string $description): void
    {
        $activity->histories()->create([
            'user'          => Auth::user()->full_name,
            'description'   => $description,
            'registered_at' => Carbon::now()
        ]);
    }

    public function listActivitiesToDashboard(string $from, string $to)
    {
        return $this->model::with(['customer','user','tag'])
            ->whereCompanyId(companyID())
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->get()->transform(function ($activity) {
                return [
                    'status'         => $activity->currentStatus(),
                    'custId'         => $activity->customer_id,
                    'custName'       => $activity->customer->name,
                    'usuId'          => $activity->user_id,
                    'usuName'        => $activity->user->full_name,
                    'tagId'          => $activity->tag_id,
                    'tagName'        => $activity->tag->name,
                    'timeEstimated'  => $activity->estimatedTime(), //compare between customers
                    'timeReal'       => $activity->total_time_real, //compare between customers
                    'startDateMonth' => Carbon::parse($activity->start_date)->format('Y-m'), //history hours company
                    'startDate'      => Carbon::parse($activity->start_date)->format('Y-m-d'), //history hours company
                ];
            });
    }

    public function progress(Collection $activities): float
    {
        if ($activities->isEmpty()) return 0;

        return round(((self::qtyCompleted($activities) / $activities->count()) * 100 ),1);
    }

    public function resume(Collection $activities): array
    {
        return [
            'total'        => $activities->count(),
            'qtyOverdue'   => self::qtyOverdue($activities),
            'qtyCompleted' => self::qtyCompleted($activities),
            'qtyPending'   => self::qtyPending($activities)
        ];
    }

    public function workPlansByUser(int $user_id, $from, $to)
    {
        return $this->model::with(['customer','tag','sub_activities','partials'])
            ->whereUserId($user_id)
            ->whereIsPlanned(true)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->get();
    }

    public function workPlanOnlyStatusPlannedsByUser(int $user_id, $from, $to)
    {
        return $this->model::with(['customer'])
            ->whereUserId($user_id)
            ->whereIsPlanned(true)
            ->whereStatus(Activity::TYPE_PLANNED)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date')
            ->get();
    }

    public function resumeByUser(Collection $activities): array
    {
        return [
            'total'        => $activities->count(),
            'qtyPlanned'   => self::qtyPlanned($activities),
            'qtyApproved'  => self::qtyApproved($activities),
            'qtyPartial'   => self::qtyPartial($activities),
            'qtyCompleted' => self::qtyCompleted($activities),
            'timeEstimate' => sumTime($activities,'time_estimate'),
        ];
    }

    public function approve(int $id)
    {
        $approvedBy = [
            'status'         => Activity::TYPE_APPROVED,
            'approved_by_id' => Auth::id(),
            'approved_date'  => now()
        ];

        $activity = $this->model::find($id);
        history(UserHistory::APPROVED,"Aprobó la actividad $activity->name",$activity);
        $this->saveHistory($activity, "Actividad aprobada");
        return $activity->update($approvedBy );
    }

    public function backToApprove(int $id)
    {
        $activity = $this->model::find($id);
        $activity->sub_activities()->delete();
        $activity->partials()->delete();

        $activity->update([
            'status'            => Activity::TYPE_APPROVED,
            'time_real'         => "00:00",
            'is_partial'        => false,
            'with_subactivities'=> false,
            'completed_date'    => null
        ]);

        $this->saveHistory($activity,'Actividad restablecida');
        history(UserHistory::RESET,"Reseteó a aprobado la actividad $activity->name",$activity);
    }

    public function backToPlanned(int $id)
    {
        $activity = $this->model::find($id);
        $activity->sub_activities()->delete();
        $activity->partials()->delete();

        $activity->update([
            'status'         => Activity::TYPE_PLANNED,
            'time_real'      => '00:00',
            'approved_by_id' => null,
            'approved_date'  => null,
            'is_partial'     => false
        ]);

        history(UserHistory::REVERSE,"Retornó la actividad planeada $activity->name",$activity);
        $this->saveHistory($activity,'Actividad revertida');
    }

    public function listActivities(string $from, string $to)
    {
        return $this->model->with('customer','created_by', 'tag','user','sub_activities','partials')
            ->whereCompanyId(companyID())
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date','desc')
            ->get();
    }

    public function listActivityByUser(int $id, $from, $to)
    {
        return $this->model->with('user','sub_activities','customer','created_by', 'tag','partials')
            ->whereUserId($id)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date')
            ->get();
    }

    public function listActivityByCustomer(int $id, $from, $to)
    {
        return $this->model->with('user','sub_activities','partials','customer','created_by', 'tag')
            ->whereCustomerId($id)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date')
            ->get();
    }

}
