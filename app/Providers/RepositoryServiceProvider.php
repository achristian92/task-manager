<?php

namespace App\Providers;


use App\Repositories\Activities\Repository\ActivityRepo;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Repository\CustomerRepo;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Repository\UserRepo;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Tags\Repository\TagRepo;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            IUser::class,
            UserRepo::class
        );

        $this->app->bind(
            ICustomer::class,
            CustomerRepo::class
        );

        $this->app->bind(
            ITag::class,
            TagRepo::class
        );

        $this->app->bind(
            IActivity::class,
            ActivityRepo::class
        );


    }


}
