<?php

namespace App\Http\Controllers\Front\Reports\Users;

use App\Exports\CounterTotalHoursCustomersByDay;
use App\Exports\Users\PlannedvsRealExport;
use App\Exports\Users\TotalHoursByCustomerExport;
use App\Exports\Users\TotalHoursByDayExport;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Http\Request;

class ReportUserController extends Controller
{
    use UploadableDocumentsTrait,DatesTrait;

    private IActivity $activityRepo;
    private IUser $userRepo;

    public function __construct(IActivity $IActivity,IUser $IUser)
    {
        $this->activityRepo = $IActivity;
        $this->userRepo = $IUser;
    }

    public function plannedvsreal(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $user = User::find($request->user_id);

        $activities = $this->userRepo->reportPlannedVsReal($user->id,$request->start_date,$request->due_date);

        $filename = $user->name.'-plan-vs-real.xlsx';
        $range = Carbon::parse($request->start_date)->format('d/m/y').'-'.Carbon::parse($request->due_date)->format('d/m/y');

        $fullPath = $this->handleDocumentS3(new PlannedvsRealExport($activities,$user->full_name,$range),$filename);

        docHistory(UserHistory::EXPORT, "Exportó reporte planificado vc real de $user->full_name",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte planificado vc real de $user->full_name - $fullPath");


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
        $range = Carbon::parse($request->start_date)->format('d/m/y').'-'.Carbon::parse($request->due_date)->format('d/m/y');

        $activities = $this->userRepo->reportTimeCustomer($user->id,$request->start_date,$request->due_date);


        $filename = $user->name."-total-horas-cliente-.xlsx";

        $fullPath = $this->handleDocumentS3(new TotalHoursByCustomerExport($activities,$user->full_name,$range),$filename);

        docHistory(UserHistory::EXPORT, "Exportó reporte tiempo trabajo por cliente de $user->full_name",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte tiempo trabajo por cliente de $user->full_name - $fullPath");


        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

    public function hoursbydays(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'yearAndMonth' => 'required|date_format:Y-m',
        ]);

        $date = $this->getDateFormats($request->yearAndMonth);
        $days = $this->rangeDays($date['from'],$date['to']);

        $user = User::find($request->user_id);
        $month = Carbon::parse($date['from'])->monthName;

        $activities = $this->userRepo->reportTimeCustomerDays($user->id,$date['from'],$date['to'],$days['range']);

        $filename = $user->name."-total-hours-dias-".$month.'.xlsx';
        $fullPath = $this->handleDocumentS3(new TotalHoursByDayExport($activities->toArray(), $days['range'], $user->name, $month),$filename);

        docHistory(UserHistory::EXPORT, "Exportó reporte tiempo trabajo por dia de $user->full_name",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte tiempo trabajo por dia de $user->full_name - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }
}
