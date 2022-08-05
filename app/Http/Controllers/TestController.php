<?php

namespace App\Http\Controllers;

use App\Exports\Activities\partials\ActivitiesSheet;
use App\Exports\Activities\TemplatePlannedExport;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Companies\Company;
use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Tag;
use App\Repositories\Users\Repository\IUser;
use Excel;

class TestController extends Controller
{
    use ActivityTrait;

    private $userRepo;
    private IActivity $activityRepo;

    public function __construct(IUser $IUser, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $company = Company::find(1);
        dd($company->getSrcLogoLocal());
    }


}
