<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class TaskManagerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        view()->composer([
            'layouts.admin.app',
        ], function ($view)
        {
            $user = Auth::user();

            $view->with([
                'userCurrent' => $user,
                'companyCurrent' => $user->company,
                'notifications' => 0
            ]);
        });
    }
}
