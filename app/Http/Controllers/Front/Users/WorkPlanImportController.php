<?php

namespace App\Http\Controllers\Front\Users;

use App\Exports\Activities\partials\ActivitiesSheet;
use App\Exports\Activities\TemplatePlannedExport;
use App\Exports\Matriz\MatrizExport;
use App\Http\Controllers\Controller;
use App\Imports\ActivitiesImport;
use App\Imports\WorkplanImport;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\ActivityDuplicateRequest;
use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tags\Tag;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class WorkPlanImportController extends Controller
{
    use UploadableTrait, DatesTrait;

    private IActivity $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }


    public function template()
    {
        history(UserHistory::EXPORT,"Exportó plantilla para importar plan");

        $customers = Customer::whereCompanyId(companyID())->orderBy('name')->pluck('name')->toArray();
        $tags = Tag::whereCompanyId(companyID())->orderBy('name')->pluck('name')->toArray();

        return Excel::download(new TemplatePlannedExport($customers,$tags), 'Plantilla-Plan-Trabajo.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:xls,xlsx'
        ]);

        if (!self::isValidHeaders())
            return response()->json([
                'msg' => "Las cabeceras no coinciden",
            ], 401);


        $url = $this->handleUploadedDocument($request->file('file_upload'),'import');

        docHistory(UserHistory::IMPORT,"Importó plan de trabajo",$url);
        history(UserHistory::IMPORT,"Importó plan de trabajo - $url");


        Excel::import(new ActivitiesImport(Auth::user()), $request->file('file_upload'));

        return response()->json([
            'msg' => 'Información cargada',
            'view' => $request->input('view','calendar')
        ], 201);
    }

    public function massplanned()
    {
        $date = $this->getDateFormats(request()->input('yearAndMonth'));

        $activities = $this->activityRepo->workPlanOnlyStatusPlannedsByUser(Auth::id(),$date['from'],$date['to'])
            ->transform(function (Activity $activity) {
                return [
                    'checked'   => false,
                    'id'        => $activity->id,
                    'name'      => $activity->name,
                    'customer'  => $activity->customer->name,
                    'startDate' => Carbon::parse($activity->start_date)->format('d/m')
                ];
            })->values();

        return response()->json([
            'activities' => $activities
        ]);

    }

    public function delete()
    {
        $IDS = request()->input('destroyIDS',[]);
        collect($IDS)->each(function ($activity_id) {
            $this->activityRepo->deleteActivity($activity_id);
        });

        return response()->json([
            'msg' => "Actividades eliminadas"
        ]);
    }

    public function duplicate(ActivityDuplicateRequest $request)
    {
        $user_id = Auth::id();

        $fromDate  = Carbon::createFromDate($request->get('from_month'));
        $fromMonth = $fromDate->format('m');
        $fromYear = $fromDate->format('Y');
        $dateTo    = Carbon::createFromDate($request->get('to_month'));
        $toMonth   = $dateTo->format('m');
        $toYear    = $dateTo->format('Y');

        $yearAndMonth = Carbon::createFromDate($fromYear, $fromMonth,1);

        Activity::whereUserId($user_id)
            ->whereIsPlanned(true)
            ->whereMonth('start_date',$yearAndMonth->format('m'))
            ->whereYear('start_date',$yearAndMonth->format('Y'))
            ->orderBy('start_date','asc')
            ->get()
            ->each(function ($activity) use ($toYear, $toMonth,$user_id) {
                $start_date_day = Carbon::createFromDate($activity->start_date)->format('d');
                $due_date_day   = Carbon::createFromDate($activity->due_date)->format('d');
                $params = [
                    'is_planned'      => true,
                    'customer_id'     => $activity->customer_id,
                    'user_id'         => $user_id,
                    'name'            => $activity->name,
                    'time_estimate'   => $activity->time_estimate,
                    'start_date'      => Carbon::createFromDate($toYear, $toMonth, $start_date_day)->format('Y-m-d'),
                    'due_date'        => Carbon::createFromDate($toYear, $toMonth, $due_date_day)->format('Y-m-d'),
                    'tag_id'          => $activity->tag_id
                ];
                $this->activityRepo->createActivity($params);
            });

        history(UserHistory::DUPLICATE,"Duplicó el plan de trabajo de $fromDate hacia $dateTo");

        return response()->json([
            'msg' => "Plan de duplicado"
        ],201);
    }


    public function isValidHeaders():bool
    {
        $headers = (new HeadingRowImport(7))->toArray(request()->file('file_upload'))[0][0];
        $diff = collect($headers)->diff(self::requiredHeaders());
        return $diff->count() === 0;
    }

    public function requiredHeaders()
    {
        return collect([
            "fecha",
            "cliente",
            "actividad",
            "horas",
            "prioridad",
            "etiqueta",
            "descripcion",
        ]);
    }
}
