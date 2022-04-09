<?php

namespace App\Http\Controllers\Front\Activities;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailActivityAssigned;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\ActivityMassApproveRequest;
use App\Repositories\Activities\Requests\ActivityRequest;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ActivityController extends Controller
{
    use ActivityTransformable, DatesTrait;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function store(ActivityRequest $request)
    {
        $activity = $this->activityRepo->createActivity($request->all());

        if ($request->has('previous'))
            $this->activityRepo->updateActivity(['previous_id' => implode(',',$request->input('previous'))], $activity->id);
        else
            $this->activityRepo->updateActivity(['previous_id' => NULL], $activity->id);


        if ($activity->isOwnerDifferent())
            $activity->notifyAssignment();

        return response()->json([
            'msg' => 'Actividad creada',
        ]);
    }

    public function show($id)
    {
        $activity = Activity::find($id);

        $data = [
            'id'                => $activity->id,
            'customer'          => $activity->customer->name,
            'tag'               => $activity->tag->name,
            'activity'          => $activity->name,
            'startDateShort'    => Carbon::parse($activity->start_date)->format('d/m'),
            'dueDateShort'      => Carbon::parse($activity->due_date)->format('d/m'),
            'isCompletedOutDate'=> $activity->isCompletedOutOfDate(),
            'dateCompleted'     => $activity->isCompletedState() ? Carbon::parse($activity->completed_date)->format('d/m') : '',
            'estimatedTime'     => $activity->estimatedTime(),
            'realTime'          => $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']),
            'status'            => $activity->statusName(),
            'currentStatus'     => $activity->currentStatus(),
            'userStatusAct'     => $activity->nameUserStateActivity(),
            'description'       => $activity->isPlanned() ? $activity->description : $activity->description2,
            'subActivities'     => $this->subActivities($activity['sub_activities']),
            'histories'         => $this->histories($activity['histories']),
            'partials'          => $this->partialActivities($activity['partials']),
            'dependencies'      => $this->dependencies($activity)
        ];

        return response()->json($data);
    }
    private function subActivities(Collection $subActivities): Collection
    {
        return $subActivities->transform(function ($subActivity) {
            return [
                'id'           => $subActivity->id,
                'name'         => $subActivity->name,
                'duration'     => $subActivity->duration,
                'completed_at' => Carbon::parse($subActivity->completed_at)->format('d/m H:i')
            ];
        });
    }
    private function histories(Collection $histories)
    {
        return $histories->transform(function ($history) {
            return [
                'user'        => $history->user,
                'dateShort'   => Carbon::parse($history->registered_at)->format('d/m H:i'),
                'description' => $history->description
            ];
        });
    }
    private function partialActivities(Collection $partialActivities): Collection
    {
        return $partialActivities->transform(function ($subActivity) {
            return [
                'id'           => $subActivity->id,
                'name'         => '(Avance) '.strtolower($subActivity->activity->name),
                'duration'     => $subActivity->duration,
                'completed_at' => Carbon::parse($subActivity->completed_at)->format('d/m H:i')
            ];
        });
    }
    private function dependencies($activity)
    {
        return collect($activity->dependenceIDS())->map(function ($activity_id) {
            $activity = Activity::find($activity_id);
            $completed_at = $activity->isCompletedState() ? $activity->completed_date : $activity->start_date;

            return [
                'name'         => $activity->name,
                'completed_at' => Carbon::parse($completed_at)->format('d/m'),
                'status'       => $activity->statusName(),
                'duration'     => $activity->isCompletedState() ?
                    $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']) :
                    $activity->estimatedTime()
            ];
        });
    }


    public function edit($id)
    {
        $activity = Activity::find($id);

        return response()->json([
            'activity' => $this->transformActivity($activity),
        ]);
    }

    public function update(ActivityRequest $request, $id)
    {
        $this->activityRepo->updateActivity($request->all(),$id);

        $activity = Activity::find($id);

        if ($request->has('previous'))
            $this->activityRepo->updateActivity(['previous_id' => implode(',',$request->input('previous'))], $activity->id);
        else
            $this->activityRepo->updateActivity(['previous_id' => NULL], $activity->id);

        if ($request->user_id !== $activity->created_by_id) {
            $activity->reassign($request);
            $this->activityRepo->saveHistory($activity,'Actividad reasignada a '.$activity->user->name);
        }

        return response()->json([
            'msg' => 'Actividad actualizada',
        ]);
    }


}
