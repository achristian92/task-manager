<?php


use App\Http\Controllers\Front\Activities\ActivityController;
use App\Http\Controllers\Front\Activities\ActivityStatusController;
use App\Http\Controllers\Front\Tags\TagController;
use App\Http\Controllers\Front\Users\UserCustomerController;
use App\Http\Controllers\Front\Users\WorkplanController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/admin', 'middleware' => ['auth']], function () {

    Route::resource('tags',TagController::class)->except(['create','show']);

});

Route::group(['prefix' => 'api', 'middleware' => ['auth']], function () {


    Route::get('dashboard/compare',App\Http\Controllers\Front\Dashboard\DashboardController::class);

    Route::get('users/{user}/customers',UserCustomerController::class);
    Route::get('users/{user_id}/workplans',WorkplanController::class);

    Route::resource("activities", ActivityController::class);
    Route::put("activities/{id}/approve", [ActivityStatusController::class,'approve']);
    Route::put("activities/{id}/reserve", [ActivityStatusController::class,'reserve']);
    Route::post("activities/mass-approve", [ActivityStatusController::class,'massapprove']);
    Route::put("activities/{id}/approve-reject", [ActivityStatusController::class,'evaluate']);


});
