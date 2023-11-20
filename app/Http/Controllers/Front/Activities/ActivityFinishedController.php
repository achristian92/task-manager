<?php

namespace App\Http\Controllers\Front\Activities;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityFinishedController extends Controller
{
    use ActivityTransformable;

    private IActivity $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(Request $request, int $id)
    {
        $act = Activity::find($id);
        $customer = Customer::find($act->customer_id);

        if(!$customer->isValidHourLimit($request->input('duration',"00:00")))
            if($customer->limitActivities())
                return $this->msgErrorJson401("Superaste el límite de horas mensuales");


        $durationRequest = request()->input('duration',"00:00");
        $description = self::isPartialActivity() ? "Actividad avanzada con $durationRequest"
            : "Actividad completada con $durationRequest";


        $daterequest = Carbon::parse(request()->input('date',Carbon::now()))->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');

        if (self::isPartialActivity())
            $data = [
                'status'     => Activity::TYPE_PARTIAL,
                'is_partial' => 1,
            ];
        else
            $data = [
                'status'                   => Activity::TYPE_COMPLETED,
                'time_real'                => $durationRequest,
                'completed_date'           => Carbon::now(),
                'different_completed_date' => $daterequest !== $now,
                'completed_date_manual'    => $daterequest,
            ];


        $this->activityRepo->updateActivity($data,$id);
        $activity = Activity::find($id);

        if (self::isPartialActivity())
            $activity->partials()->create([
                'duration'     => $durationRequest,
                'completed_at' => Carbon::now()
            ]);

        $this->activityRepo->saveHistory($activity,$description);

        if ($daterequest !== $now)
            $this->activityRepo->saveHistory(Activity::find($id),'Pendiente de aprobación de fecha');

        history(UserHistory::UPDATED,"$description - $activity->name",$activity);

        $activity->total_time_real = sumArraysTime([$activity->total_time_real,$durationRequest]);
        $activity->save();

        return response()->json([
            'msg'      => $description,
            'activity' => $this->transformActivityAdvance(Activity::find($id))
        ]);
    }

    private function isPartialActivity(): bool
    {
        return request()->filled('is_partial') && request()->input('is_partial',false);
    }
}
