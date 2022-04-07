<?php

namespace App\Http\Controllers\User\WorkPlans;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;
use Auth;

class MyWorkPlanController extends Controller
{
    private $tagRepo, $userRepo;

    public function __construct (ITag $ITag,IUser $IUser) {
        $this->tagRepo = $ITag;
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        return view('user.workplans.index', [
            'customers'  => $this->userRepo->listCustomers(Auth::user()),
            'status'     => Activity::TYPE_STATE,
            'tags'       => $this->tagRepo->listTags(),
        ]);
    }
}
