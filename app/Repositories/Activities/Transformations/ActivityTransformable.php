<?php

namespace App\Repositories\Activities\Transformations;

use App\Repositories\Activities\Activity;
use App\Repositories\PartialActivities\PartialActivity;
use App\Repositories\SubActivities\SubActivity;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait ActivityTransformable
{
    public function transformActivity(Activity $activity) // use calendario
    {
        return [
            'id'              => $activity->id,
            'customer_id'     => $activity->customer_id,
            'user_id'         => $activity->user_id,
            'name'            => $activity->name,
            'time_estimate'   => $activity->estimatedTime(),
            'start_date'      => $activity->start_date,
            'due_date'        => $activity->due_date,
            'deadline'        => $activity->deadline,
            'is_priority'     => $activity->is_priority,
            'tag_id'          => $activity->tag_id ?? '',
            'previous_ids'    => $activity->dependenceIDS(),
            'description'     => $activity->description,
            'canUpdate'       => $activity->canUpdate(),
            'canApprove'      => $activity->canApprove(),
            'canReverse'      => $activity->canReverse(),
            'canDestroy'      => $activity->canDestroy()
        ];
    }

    public function transformActivityAdvance(Activity $activity)
    {
        return [
            'id'                    => $activity->id,
            'isPlanned'             => $activity->isPlanned(),
            'customerId'            => $activity->customer->id,
            'customerName'          => $activity->customer->name,
            'customerNameShort'     => Str::limit($activity->customer->name,15),
            'userId'                => $activity->user_id, //new
            'userName'              => $activity->user->full_name,
            'userNameShort'         => Str::limit($activity->user->full_name,15),
            'activityName'          => $activity->name,
            'activityNameShort'     => Str::limit($activity->name,55),
            'startDateShort'        => Carbon::parse($activity->start_date)->format('d/m'),
            'startDateMonth'        => Carbon::parse($activity->start_date)->format('Y-m'), //filter last months(agroup)
            'dueDateShort'          => Carbon::parse($activity->due_date)->format('d/m'),
            'completedDateShort'    => $activity->completed_date ? Carbon::parse($activity->completed_date)->format('d/m') : '',
            'recordDateShort'       => $activity->completed_date_manual ? Carbon::parse($activity->completed_date_manual)->format('d/m') : '',
            'changeDateBy'          => $activity->approved_change_date_by ?? '',
            'status'                => $activity->currentStatus(),
            'statusColorBtnOutline' => _colorBtnOutline($activity->currentStatus()),
            'statusName'            => $activity->statusName(),
            'estimatedTime'         => $activity->estimatedTime(),
            'realTime'              => $activity->totalTimeEntered($activity['sub_activities'],$activity['partials']),
            'startDate'             => Carbon::parse($activity->start_date)->format('Y-m-d'),
            'dueDate'               => Carbon::parse($activity->due_date)->format('Y-m-d'),
            'isPriority'            => (bool) $activity->is_priority,
            'qtySubActivities'      => $activity->sub_activities->count(),
            'canCompleted'          => $activity->canCompleted(),
            'canDestroy'            => $activity->canDestroy(),
            'isDependenciesCompleted' => $activity->isDependenciesCompleted(),
            'qtyDependencies'       => count($activity->dependenceIDS()),
            'tagId'                 => $activity->tag->id,
            'tagName'               => $activity->tag->name,
            'tagColor'              => $activity->tag->color,
            'diff_completed_date'   => (bool) $activity->different_completed_date,
            'is_approved_change_date' => (bool) $activity->is_approved_change_date,

        ];
    }

    public function transformActivityReport(Activity $activity,bool $onlyPlanned = false)
    {
        $dateStartOrCompleted = $activity->start_date;
        if (! $onlyPlanned)
            $dateStartOrCompleted = $activity->isCompletedState() ? $activity->completed_date : $activity->start_date;

        return [
            'isPlanned'     => (bool) $activity->is_planned,
            'name'          => $activity->name,
            'userId'        => $activity->user->id,
            'counter'       => $activity->user->full_name,
            'customer'      => $activity->customer->name,
            'startDate'     => Carbon::parse($dateStartOrCompleted)->format('Y-m-d'),
            'startDateFormat'=> Carbon::parse($dateStartOrCompleted)->format('d/m/y'),
            'estimatedTime' => $activity->estimatedTime(),
            'realTime'      => $activity->currentRecordedTime(),
            'statusName'    => $activity->statusName(),
            'color'         => _colorStatusHex($activity->currentStatus()),
            'tag'          =>  $activity->tag->name,
            'description'   => $activity->description,
        ];
    }

    public function transformSubActivityReport(SubActivity $subActivity)
    {
        return  [
            'isPlanned'     => false,
            'name'          => '(SubActividad) '. $subActivity->name .'('.$subActivity->activity->name .')',
            'counter'       => $subActivity->activity->user->full_name,
            'customer'      => $subActivity->activity->customer->name,
            'startDate'     => Carbon::parse($subActivity->completed_at)->format('Y-m-d'),
            'estimatedTime' => '00:00',
            'realTime'      => $subActivity->duration,
            'statusName'    => Activity::TYPE_STATE[Activity::TYPE_COMPLETED],
            'color'         => _colorStatusHex(Activity::TYPE_COMPLETED),
            'tag'           => $subActivity->activity->tag->name
        ];
    }

    public function transformPartialActivityReport(PartialActivity $partial)
    {
        return  [
            'isPlanned'     => false,
            'name'          => '(Avance) '.strtolower($partial->activity->name),
            'counter'       => $partial->activity->user->full_name,
            'customer'      => $partial->activity->customer->name,
            'startDate'     => Carbon::parse($partial->completed_at)->format('Y-m-d'),
            'estimatedTime' => '00:00',
            'realTime'      => $partial->duration,
            'statusName'    => Activity::TYPE_STATE[Activity::TYPE_COMPLETED],
            'color'         => _colorStatusHex(Activity::TYPE_COMPLETED),
            'tag'          => $partial->activity->tag->name
        ];
    }
}
