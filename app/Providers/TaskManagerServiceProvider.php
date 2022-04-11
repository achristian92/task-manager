<?php

namespace App\Providers;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TaskManagerServiceProvider extends ServiceProvider
{
    use ActivityTrait;

    public function boot()
    {
        view()->composer([
            'layouts.admin.app',
        ], function ($view)
        {
            $user = Auth::user();

            $view->with([
                'userCurrent'    => $user,
                'companyCurrent' => $user->company,
                'notifications'  => self::evaluations($user)
            ]);
        });


        view()->composer([
            'layouts.admin.sidebard',
        ], function ($view) {
            $view->with([
                'overdue'=> self::totalOverdue(),
            ]);
        });

        view()->composer('admin.users.partials.row', function ($view) {
            $view->with('userv2', Auth::user());
        });

    }

    private function evaluations(User $user)
    {
        $yearAndMonth = Carbon::createFromDate(null, null,1);  // Year and month defaults to current year

        $month = $yearAndMonth->format('m');
        $year = $yearAndMonth->format('Y');

        return Activity::with('user')
            ->whereCompanyId($user->company_id)
            ->whereMonth('start_date',$month)
            ->whereYear('start_date',$year)
            ->where('different_completed_date',true)
            ->whereNull('approved_change_date_by')
            ->latest()
            ->take(6)
            ->get();
    }

    private function totalOverdue()
    {
        $yearAndMonth = Carbon::createFromDate(null, null,1);  // Year and month defaults to current year

        $month = $yearAndMonth->format('m');
        $year = $yearAndMonth->format('Y');

        $data = Activity::whereCompanyId(companyID())
            ->whereIsPlanned(true)
            ->whereMonth('start_date',$month)
            ->whereYear('start_date',$year)
            ->get()
            ->transform(function (Activity $activity) {
                return [
                    'user_id'    => $activity->user_id,
                    'status'     => $activity->currentStatus(),
                    'startDate'  => Carbon::parse($activity->start_date)->format('Y-m-d'),
                    'dueDate'    => Carbon::parse($activity->due_date)->format('Y-m-d'),
                ];
            });
        $own = 0;
        if (Auth::user()->hasRole('Usuario'))
            $own = collect($this->filterOverdue(Carbon::now(),$data->where('user_id',Auth::id())))->count();


        return [
            'general' => collect($this->filterOverdue(Carbon::now(),$data))->count(),
            'own'     => $own
        ];
    }
}
