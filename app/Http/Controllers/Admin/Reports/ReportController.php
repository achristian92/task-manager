<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Users\Repository\IUser;

class ReportController extends Controller
{
    private ICustomer $customerRepo;
    private IUser $userRepo;

    public function __construct (IUser $IUser, ICustomer $ICustomer) {
        $this->customerRepo = $ICustomer;
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        $user = \Auth::user();

        return view('admin.reports.index',[
            'users' => $this->userRepo->listAssignedUsers($user),
            'customers' => $this->customerRepo->listCustomers()
        ]);
    }
}
