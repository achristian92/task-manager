<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Users\Repository\IUser;

class UserController extends Controller
{
    private $userRepo, $customerRepo;

    public function __construct(IUser $IUser, ICustomer $ICustomer)
    {
        $this->userRepo = $IUser;
        $this->customerRepo = $ICustomer;
    }

    public function index()
    {
        return view('admin.users.index',[
            'users' => $this->userRepo->listAllUsers()
        ]);
    }

    public function show()
    {

    }


}
