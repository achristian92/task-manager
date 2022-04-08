<?php

namespace App\Providers;

use App\Repositories\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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


        view()->composer('admin.users.partials.row', function ($view) {
            $view->with('userv2', Auth::user());
        });

    }
}
