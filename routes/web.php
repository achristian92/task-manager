<?php

use App\Http\Controllers\Admin\Customers\CustomerController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\History\DocumentController;
use App\Http\Controllers\Admin\History\UserController as UserHistoryController;
use App\Http\Controllers\Admin\Imbox\ImboxController;
use App\Http\Controllers\Admin\Reports\ReportController;
use App\Http\Controllers\Admin\Tags\TagController;
use App\Http\Controllers\Admin\Tracks\TrackController;
use App\Http\Controllers\Admin\Users\UserActionController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\WorkPlans\WorkPlanController;
use App\Http\Controllers\Setting\Companies\CompanyController;
use App\Http\Controllers\Setting\Profiles\ProfileController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\User\Imbox\MyImboxController;
use App\Http\Controllers\User\Reports\MyReportController;
use App\Http\Controllers\User\Tracks\MyTrackController;
use App\Http\Controllers\User\WorkPlans\MyWorkPlanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();
Auth::routes([
    "register" => false,
    'verify' => false
]);

Route::get('/test', TestController::class);

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.' ], function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard.index');

    Route::resource('customers', CustomerController::class);

    Route::resource('users', UserController::class);
    Route::get('users/{user}/history', [UserController::class,'history'])->name('users.history');
    Route::get('users/{user}/documents', [UserController::class,'document'])->name('users.document');
    Route::get('users/{user}/enable',[UserActionController::class,'enable'])->name('users.enable');
    Route::get('users/{user}/disable',[UserActionController::class,'disable'])->name('users.disable');
    Route::get('users/{user}/send-credentials',[UserActionController::class,'sendCredentials'])->name('users.credentials');

    Route::get('tags', TagController::class)->name('tags.index');

    Route::get('workplans', WorkPlanController::class)->name('workplans.index');

    Route::get('imbox', ImboxController::class)->name('imbox.index');

    Route::resource('tracks', TrackController::class)->only('index','show');

    Route::get('reports', ReportController::class)->name('reports.index');

    Route::resource('histories', UserHistoryController::class)->only('index','show');

    Route::get('documents', DocumentController::class)->name('documents.index');

});

Route::group(['prefix' => 'user', 'middleware' => ['auth'], 'as' => 'user.' ], function () {

    Route::get('my-workplans', MyWorkPlanController::class)->name('workplans.index');
    Route::get('my-tracks',MyTrackController::class)->name('tracks.index');
    Route::get('my-imbox',MyImboxController::class)->name('imbox.index');
    Route::get('my-reports',MyReportController::class)->name('reports.index');
});


Route::group(['prefix' => 'setting', 'middleware' => ['auth'], 'as' => 'setting.' ], function () {
    Route::resource('profile', ProfileController::class)->only(['edit','update']);
    Route::resource('company', CompanyController::class)->only(['edit','update']);
});



require __DIR__.'/web_front.php';
