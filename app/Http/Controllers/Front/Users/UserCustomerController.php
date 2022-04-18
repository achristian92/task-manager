<?php

namespace App\Http\Controllers\Front\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Customers\CustomerTransformable;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;

class UserCustomerController extends Controller
{
    use CustomerTransformable;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(User $user)
    {
        $customers = $this->userRepo->listCustomers($user)
            ->transform(function ($customer) {
                return $this->trasformCustomerToSelect2($customer);
        });

        return response()->json([
            'customers' => $customers,
        ]);

    }
}
