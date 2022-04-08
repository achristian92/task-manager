<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;

class UserActionController extends Controller
{
    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function enable(User $user)
    {
        $user->update(['is_active'=> true]);

        history(UserHistory::ENABLE,"Habilitó al usuario $user->full_name");

        return redirect()->route('admin.users.index')->with('success','Usuario activado.');
    }

    public function disable(User $user)
    {
        $user->update(['is_active'=> false]);

        history(UserHistory::DISABLE,"Desahabilitó al usuario $user->full_name");

        return redirect()->route('admin.users.index')->with('success','Usuario desactivado.');
    }

    public function sendCredentials(User $user)
    {
        $this->userRepo->sendEmailNewCredentials($user);

        return redirect()->route('admin.users.index')->with('success','Credenciales enviado.');
    }


}
