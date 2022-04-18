<?php


use App\Http\Controllers\Front\Activities\ActivityController;
use App\Http\Controllers\Front\Activities\ActivityFinishedController;
use App\Http\Controllers\Front\Activities\ActivityStatusController;
use App\Http\Controllers\Front\Reports\Activities\ActivityExportController;
use App\Http\Controllers\Front\Reports\Customers\ReportCustomerController;
use App\Http\Controllers\Front\Reports\Users\ReportUserController;
use App\Http\Controllers\Front\Reports\Users\ReportWorkplanController;
use App\Http\Controllers\Front\Tags\TagController;
use App\Http\Controllers\Front\Users\UserCustomerController;
use App\Http\Controllers\Front\Users\WorkplanController;
use App\Http\Controllers\Front\Users\WorkPlanImportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/admin', 'middleware' => ['auth']], function () {

    Route::resource('tags',TagController::class)->except(['create','show']);

});

Route::group(['prefix' => 'api', 'middleware' => ['auth']], function () {


    Route::get('dashboard/compare',App\Http\Controllers\Front\Dashboard\DashboardController::class);

    Route::get('users/{user}/customers',UserCustomerController::class);

    Route::get('users/{user}/workplans',WorkplanController::class);
    Route::get('my-workplans/matriz', [WorkPlanImportController::class,'matriz']);
    Route::get('my-workplans/template', [WorkPlanImportController::class,'template']);
    Route::post('my-workplans/import', [WorkPlanImportController::class,'import']);
    Route::get('my-workplans/mass-planned', [WorkPlanImportController::class,'massplanned']);
    Route::post('my-workplans/mass-delete', [WorkPlanImportController::class,'delete']);
    Route::post('my-workplans/duplicate', [WorkPlanImportController::class,'duplicate']);

    Route::resource("activities", ActivityController::class);
    Route::post("activities/{id}/sub", [ActivityController::class,'sub']);
    Route::delete("activities/sub/{id}", [ActivityController::class,'deletesub']);
    Route::post("activity/new", [ActivityController::class,'new']);

    Route::post("activities/mass-approve", [ActivityStatusController::class,'massapprove']);
    Route::put("activities/{id}/approve", [ActivityStatusController::class,'approve']);
    Route::put("activities/{id}/reserve", [ActivityStatusController::class,'reserve']);
    Route::put("activities/{id}/reset", [ActivityStatusController::class,'reset']);
    Route::put("activities/{id}/approve-reject", [ActivityStatusController::class,'evaluate']);
    Route::put("activities/{id}/finished", ActivityFinishedController::class);

    Route::get('reports/users/{user}/workplans',[ReportWorkplanController::class,'list']);
    Route::get('reports/users/{user}/workplan-days',[ReportWorkplanController::class,'day']);

    Route::get("reports/users/planned-vs-real", [ReportUserController::class,'plannedvsreal']);
    Route::get("reports/users/time-worked-by-customer", [ReportUserController::class,'hoursbycustomer']);
    Route::get("reports/users/time-worked-by-day", [ReportUserController::class,'hoursbydays']);

    Route::get("reports/customers/time-worked-by-month", [ReportCustomerController::class,'day']);
    Route::get("reports/customers/list-users-working", [ReportCustomerController::class,'user']);
    Route::get("reports/customers/monthly-working-time-history", [ReportCustomerController::class,'history']);
    Route::get("reports/customers/activities-tags", [ReportCustomerController::class,'tag']);

    Route::get("reports/activities/list-activities", ActivityExportController::class);
});
