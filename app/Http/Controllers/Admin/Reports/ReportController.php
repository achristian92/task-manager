<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\Customers\CustomerTransformable;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Transformations\UserTransformable;

class ReportController extends Controller
{
    use UserTransformable, CustomerTransformable;

    private ICustomer $customerRepo;
    private IUser $userRepo;

    public function __construct (IUser $IUser, ICustomer $ICustomer) {
        $this->customerRepo = $ICustomer;
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        $user = \Auth::user();

        $users = $this->userRepo->listAssignedUsers($user)
            ->transform(function ($user) {
                return $this->trasformUserToSelect2($user);
            });

        $customers = $this->customerRepo->listCustomers()
            ->transform(function ($customer) {
                return $this->trasformCustomerToSelect2($customer);
            });
        return view('admin.reports.index',[
            'users'     => $users,
            'customers' => $customers
        ]);
    }
}
