<?php

namespace App\Http\Controllers\Admin\WorkPlans;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;
use Auth;

class WorkPlanController extends Controller
{
    private $userRepo, $customerRepo, $tagRepo;

    public function __construct(IUser $IUser,ICustomer $ICustomer,ITag $ITag)
    {
        $this->userRepo = $IUser;
        $this->customerRepo = $ICustomer;
        $this->tagRepo = $ITag;

    }

    public function __invoke()
    {
        $user = Auth::user();
        return view('admin.workplans.index', [
            'assignedUsers' => $this->userRepo->listAssignedUsers($user),
            'statuses'      => Activity::getStatusesList(),
            'customers'    => $this->customerRepo->listCustomers(),
            'usersAll'     => $this->userRepo->listAllUsers(),
            'tags'         => $this->tagRepo->listTags(),
        ]);
    }
}
