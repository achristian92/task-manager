<?php

namespace App\Repositories\Customers;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Files\File;
use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use softDeletes, HasFactory, ActivityTransformable;

    protected $appends = ['src_logo'];

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
    public function limitActivities()
    {
        return (int) $this->limit_hours === 1 && !empty($this->hours);
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'full_name' => '--'
        ]);
    }

    public function getSrcLogoAttribute(): string
    {
        return $this->src_img ?: asset('img/customer-default.png');
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function getTotalTime($from, $to)
    {
        $activities = Activity::with('user','sub_activities','partials','customer','created_by', 'tag')
            ->whereCustomerId($this->id)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date')
            ->get()
            ->transform(function ($activity) {
                return [
                    'isPlanned' => $activity->isPlanned(),
                    'status'    => $activity->currentStatus(),
                    'realTime'  => $activity->totalTimeEntered($activity['sub_activities'],$activity['partials']),
                    'estimatedTime' => $activity->estimatedTime(),
                ];
            });


        $totalNotPlanned = $activities->where('isPlanned',false)->where('status',Activity::TYPE_COMPLETED);
        $totalPlanned = $activities->where('isPlanned',TRUE)->where('status',Activity::TYPE_PLANNED);
        $totalDiffPlanned = $activities->where('isPlanned',TRUE)->where('status','!=',Activity::TYPE_PLANNED);

        $timeNotPlanned = Arr::pluck($totalNotPlanned,'realTime');
        $timePlanned = Arr::pluck($totalPlanned,'estimatedTime');
        $timeDiffPlanned = Arr::pluck($totalDiffPlanned,'realTime');

        return sumArraysTime(array_merge($timeNotPlanned,$timePlanned,$timeDiffPlanned));

    }
    public function isValidHourLimit($newTime = "00:00")
    {
        if(empty($this->hours))
            return true;

        $from = now()->startOfMonth()->format('Y-m-d');
        $to = now()->endOfMonth()->format('Y-m-d');

        $activities = Activity::with('user','sub_activities','partials','customer','created_by', 'tag')
            ->whereCustomerId($this->id)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date')
            ->get()
            ->transform(function ($activity) {
                return $this->transformActivityAdvance($activity);
            });

        $totalNotPlanned = $activities->where('isPlanned',false)->where('status',Activity::TYPE_COMPLETED);
        $totalPlanned = $activities->where('isPlanned',TRUE)->where('status',Activity::TYPE_PLANNED);
        $totalDiffPlanned = $activities->where('isPlanned',TRUE)->where('status','!=',Activity::TYPE_PLANNED);

        $timeNotPlanned = Arr::pluck($totalNotPlanned,'realTime');
        $timePlanned = Arr::pluck($totalPlanned,'estimatedTime');
        $timeDiffPlanned = Arr::pluck($totalDiffPlanned,'realTime');

        $sumTotalMinutes = convertMinutes(sumArraysTime(array_merge($timeNotPlanned,$timePlanned,$timeDiffPlanned)));
        $limitMinutes = convertMinutes($this->hours);
        $addMinutes = convertMinutes($newTime);
//       Log::info("Limit: ".$limitMinutes);
//       Log::info("total time: ".$sumTotalMinutes);
//       Log::info("add time: ".$addMinutes);
        return $sumTotalMinutes + $addMinutes < $limitMinutes;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if (Auth::check())
                $record->company_id  = companyID();
        });
    }


}
