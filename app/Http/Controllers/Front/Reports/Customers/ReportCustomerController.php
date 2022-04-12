<?php

namespace App\Http\Controllers\Front\Reports\Customers;

use App\Exports\CustomerHistoryHours;
use App\Exports\CustomerListCounterWork;
use App\Exports\Customers\ActivitiesByTagExport;
use App\Exports\Customers\ActivityTagExport;
use App\Exports\Customers\HistoryHoursExport;
use App\Exports\Customers\TotalHoursByDayExport;
use App\Exports\Customers\UserListExport;
use App\Http\Controllers\Controller;
use App\Repositories\Customers\Customer;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportCustomerController extends Controller
{
    use UploadableDocumentsTrait, DatesTrait;

    private ICustomer $customerRepo;

    public function __construct(ICustomer $ICustomer)
    {
        $this->customerRepo = $ICustomer;
    }

    public function day(Request $request)
    {
        $request->validate([
            'yearAndMonth' => 'required|date_format:Y-m',
        ]);

        $date = $this->getDateFormats($request->yearAndMonth);
        $days = $this->rangeDays($date['from'],$date['to']);
        $month = Carbon::parse($date['from'])->monthName;

        $data = $this->customerRepo->reportTimeCustomerDays($date['from'],$date['to'],$days['range']);

        $filename = 'Clientes-horas-mensual'.$month.'.xlsx';
        $fullPath = $this->handleDocumentS3(new TotalHoursByDayExport($data->toArray(), $days['range'], $month),$filename);

        docHistory(UserHistory::EXPORT,"Exportó reporte horas trabajas por clientes",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte horas trabajas por clientes");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

    public function user(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'yearAndMonth' => 'required|date_format:Y-m',
        ]);

        $customer = Customer::find($request->customer_id);
        $date = $this->getDateFormats($request->yearAndMonth);
        $month = Carbon::parse($date['from'])->monthName;
        $days = $this->rangeDays($date['from'],$date['to']);

        $activities = $this->customerRepo->reportUserWorked($request->customer_id,$date['from'],$date['to'],$days['range']);

        $filename = 'Clientes-lista-contadores'.$month.'.xlsx';
        $fullPath = $this->handleDocumentS3(new UserListExport($activities->toArray(),$days['range'],$month,$customer->name),$filename);

        docHistory(UserHistory::EXPORT,"Exportó reporte lista de trabajores del cliente $customer->name",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte lista de trabajores del cliente $customer->name");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

    public function history(Request $request)
    {
        $request->validate([
            'yearAndMonth' => 'required|date_format:Y-m',
        ]);

        $date = $this->getDateFormats($request->yearAndMonth);
        $months = $this->subMonths($date['from']);

        $activities = $this->customerRepo->reportHistory($months['fromYmd'],$date['to'])
            ->groupBy('customerName')
            ->map(function ($activities, $customer) use($months) {
                foreach ($months['formatYm'] as $month) {
                    $byMonth = $activities->where('startDateMonth',$month)->pluck('totalRealTime')->toArray();
                    $hours[] = sumArraysTime($byMonth);
                }
                return [
                    'name'        => $customer,
                    'hoursMonths' => $hours,
                ];
            })->values();

        $filename = 'Clientes-historial-horas.xlsx';
        $fullPath = $this->handleDocumentS3(new HistoryHoursExport($activities->toArray(),$months['names']),$filename);

        docHistory(UserHistory::EXPORT,"Exportó historial horas clientes",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte historial horas clientes - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

    public function tag(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required',
            'yearAndMonth' => 'required|date_format:Y-m',
        ]);

        $date = $this->getDateFormats($request->yearAndMonth);

        $activities = $this->customerRepo->reportactivityByTag($request->customer_id, $date['from'], $date['to']);

        $customer = Customer::find($request->customer_id);
        $filename = $customer->ruc."-actividades-por-etiqueta.xlsx";
        $range = Carbon::parse($date['from'])->format('d/m/y') .' - '. Carbon::parse($date['to'])->format('d/m/y');

        $fullPath = $this->handleDocumentS3(new ActivitiesByTagExport($activities,$customer->name,$range),$filename);

        docHistory(UserHistory::EXPORT, "Exportó reporte por etiquetas de $customer->name",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte planificado vc real de $customer->name - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);

    }
}
