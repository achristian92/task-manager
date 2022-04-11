<?php

namespace App\Http\Controllers\Front\Reports\Users;

use App\Exports\CounterTotalHoursCustomers;
use App\Exports\Users\PlannedvsRealExport;
use App\Exports\Users\TotalHoursByCustomerExport;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Http\Request;

class ReportUserController extends Controller
{
    use UploadableDocumentsTrait;

    private IActivity $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function plannedvsreal(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $user = User::find($request->user_id);

        $activities = $this->activityRepo->queryActivitiesReport($request->start_date,$request->due_date)
            ->where('userId',$user->id)
            ->groupBy('customer')
            ->transform(function ($activities, $customer) {
                return [
                    'customer'           => $customer,
                    'totalEstimatedTime' => sumTime(new DatabaseCollection($activities),'estimatedTime'),
                    'totalRealTime'      => sumTime(new DatabaseCollection($activities),'realTime'),
                    'activities'         => $activities
                ];
            })->values();

        $filename = $user->name.'-plan-vs-real.xlsx';
        $range = $request->start_date.'-'.$request->due_date;

        $fullPath = $this->handleDocumentS3(new PlannedvsRealExport($activities,$user->full_name,$range),$filename);

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

    public function hoursbycustomer(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $user = User::find($request->user_id);
        $range = $request->start_date.' / '.$request->due_date;

        $activities = $this->activityRepo->queryActivitiesReport($request->start_date,$request->due_date)
            ->where('userId',$user->id)
            ->transform(function ($activity) {
                return [
                    'customer'      => $activity->customer->name,
                    'estimatedTime' => $activity->estimatedTime(),
                    'realTime'      => $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']),
                ];
            })
            ->groupBy('customer')
            ->map(function ($activities, $customer) use ($range) {
                return [
                    'date'               => $range,
                    'customer'           => $customer,
                    'totalEstimatedTime' => sumTime($activities,'estimatedTime'),
                    'totalRealTime'      => sumTime($activities,'realTime')
                ];
            })->sortBy('customer')->values();

        $filename = $user->name."-tiempo-trabajo-cliente-".$range.'.xlsx';
        $fullPath = $this->handleDocumentS3(new TotalHoursByCustomerExport($activities,$user->full_name,$range),$filename);

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }
}
