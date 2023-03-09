<?php

namespace App\Http\Controllers\Front\Reports\Users;

use App\Exports\PlanCounterExport;
use App\Exports\PlanCounterExportDays;
use App\Exports\Users\WorkplanDaysExport;
use App\Exports\Users\WorkplansExport;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportWorkplanController extends Controller
{
    use DatesTrait, ActivityTransformable;

    private IActivity $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function list(Request $request, User $user)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        $activities = $this->activityRepo->workPlansByUser($user->id,$date['from'],$date['to'])
            ->transform(function ($activity) {
                return $this->transformActivityReport($activity,true);
            })->sortBy('startDate');

        $datev2 = Carbon::parse($date['from']);
        $monthName = ucfirst($datev2->monthName)."-".$datev2->year;
        $nameFile = strtoupper('R-PLANDETRABAJO-'.$user->name.'-'.$monthName.'.xlsx');

        history(UserHistory::EXPORT,"Exportó plan de trabajo del usuario $user->full_name - $monthName");

        return Excel::download(new WorkplansExport($activities,$user->full_name,$monthName), $nameFile);
    }

    public function day(Request $request, User $user)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));
        $rangeDays = $this->rangeDays($date['from'],$date['to'])['range'];

        $activities = $this->activityRepo->workPlansByUser($user->id,$date['from'],$date['to'])
            ->transform(function ($activity) {
                return $this->transformActivityReport($activity,true);
            })
            ->groupBy('customer')->map(function ($activities, $customer) use ($rangeDays) {
                return [
                    'customer' => $customer,
                    'dates' => $this->addTimeByDate($rangeDays,$activities,'estimatedTime'),
                    'total' => sumTime($activities,'estimatedTime')
                ];
            })
            ->sortBy('customer')->values();

        $datev2 = Carbon::parse($date['from']);
        $monthName = ucfirst($datev2->monthName)."-".$datev2->year;
        $nameFile = strtoupper('R-PLANDETRABAJOPORDÍAS-'.$user->name.'-'.$monthName.'.xlsx');

        history(UserHistory::EXPORT,"Exportó plan de trabajo por dia del usuario $user->full_name - $monthName");

        return Excel::download(new WorkplanDaysExport($activities->toArray(),$rangeDays,$user->full_name,$monthName), $nameFile);
    }



}
