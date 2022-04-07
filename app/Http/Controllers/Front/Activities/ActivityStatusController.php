<?php

namespace App\Http\Controllers\Front\Activities;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\ActivityMassApproveRequest;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tools\DatesTrait;
use Illuminate\Http\Request;

class ActivityStatusController extends Controller
{
    use ActivityTransformable, DatesTrait;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }
    public function destroy(int $id)
    {
        $this->activityRepo->deleteActivity($id);

        return response()->json([
            'msg' => "Actividad eliminada"
        ]);
    }

    public function approve($id)
    {
        $this->activityRepo->approve($id);

        return response()->json([
            'msg' => "Actividad aprobada"
        ]);
    }

    public function massapprove(ActivityMassApproveRequest $request)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        $this->activityRepo->workPlansByUser($request->user_id,$date['from'],$date['to'])
            ->where('status',Activity::TYPE_PLANNED)
            ->pluck('id')
            ->each(function ($activity_id)  {
                $this->activityRepo->approve($activity_id);
            });

        return response()->json([
            'msg' => "Actividades aprobadas"
        ]);
    }

    public function reserve($id)
    {
        $this->activityRepo->backToPlanned($id);

        return response()->json([
            'msg' => "Actividad revertida"
        ]);
    }

    public function evaluate(Request $request, $id)
    {
        $activity = Activity::find($id);

        if ($request->input('approved',false)) {
            $this->activityRepo->saveHistory($activity,'Aprobado el cambio de fecha');
            $activity->start_date     = $activity->completed_date_manual;
            $activity->due_date       = $activity->completed_date_manual;
            $activity->completed_date = $activity->completed_date_manual;
        }

        $activity->approved_change_date_by = \Auth::id();
        $activity->approved_change_date = now();
        $activity->save();

        return response()->json([
            'msg' => 'Registro actualizado'
        ]);
    }
}
