<?php

namespace App\Http\Controllers\Front\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkplanController extends Controller
{
    use DatesTrait;

    private $userRepo, $activityRepo;

    public function __construct(IUser $IUser, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke(Request $request, int $user_id)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        $activities = $this->activityRepo->workPlansByUser($user_id,$date['from'],$date['to']);

        $resume = $this->activityRepo->resumeByUser($activities);

        return response()->json([
            'counters' => $resume,
            'user_id'  => $user_id,
            'calendar' => $this->calendar($activities),
            'list'     => $this->list($activities)
        ]);
    }

    private function calendar($activities)
    {
        return $activities->map(function ($activity) {
            return [
                'id'        => $activity->id,
                'title'     => $activity->name,
                'start'     => $activity->start_date,
                'allDay'    => true,
                'className' => _colorStatusBg($activity->status)
            ];
        })->values();
    }

    private function list($activities)
    {
        return $activities->transform(function ($activity) {
            return self::transformToList($activity);
        })
            ->groupBy('customer')->map(function ($activities,$customer) {
            $progress = $this->activityRepo->progress($activities);
            return  [
                'name'                => $customer,
                'qtyActivities'       => $activities->count(),
                'activities'          => $activities,
                'progress'            => $progress,
                'bgProgress'          => _bgProgress($progress),
                'sumHoursEstCustomer' => sumTime($activities,'estimatedTime')
            ];
        })->values();
    }

    private function transformToList(Activity $activity)
    {
        return [
            'id'                    => $activity->id,
            'customer_id'           => $activity->customer->id,
            'customer'              => Str::limit($activity->customer->name,20),
            'nameActivity'          => Str::limit($activity->name,40),
            'estimatedTime'         => $activity->estimatedTime(),
            'duration'              => $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']),
            'startDateCalendar'     => Carbon::parse($activity->start_date)->format('Y-m-d'),
            'startDate'             => Carbon::parse($activity->start_date)->format('d/m'),
            'dueDate'               => $activity->due_date,
            'is_priority'           => $activity->is_priority,
            'colorPriority'         => $activity->is_priority ? 'text-danger' : '',
            'status'                => $activity->currentStatus(),
            'statusName'            => $activity->statusName(),
            'nameUserStateActivity' => $activity->nameUserStateActivity(),
            'colorState'            => _colorStatusBg($activity->currentStatus()),
            'tagId'                => $activity->tag->id,
            'tagName'               => $activity->tag->name,
            'tagColor'               => $activity->tag->color,
            'checked'               => false
        ];
    }

}
