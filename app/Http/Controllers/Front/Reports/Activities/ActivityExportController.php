<?php

namespace App\Http\Controllers\Front\Reports\Activities;

use App\Exports\Activities\ActivityExport;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTraitReport;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityExportController extends Controller
{
    use UploadableDocumentsTrait,DatesTrait, ActivityTraitReport;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $activities = $this->activitiesAllReport($request->start_date,$request->due_date)
            ->sortBy('startDate');

        $filename = "Actividades-reporte.xlsx";
        $range = Carbon::parse($request->start_date)->format('d/m/y') .' - '. Carbon::parse($request->due_date)->format('d/m/y');

        $fullPath = $this->handleDocumentS3(new ActivityExport($activities->toArray(),$range),$filename);

        docHistory(UserHistory::EXPORT,"Exportó reporte actividades $range",$fullPath);
        history(UserHistory::EXPORT,"Exportó reporte actividades $range - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }
}
