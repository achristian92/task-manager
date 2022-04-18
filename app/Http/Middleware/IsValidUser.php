<?php

namespace App\Http\Middleware;

use App\Repositories\Users\Repository\IUser;
use Closure;
use Illuminate\Http\Request;

class IsValidUser
{
    private IUser $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function handle(Request $request, Closure $next)
    {
        $id = $request->segment(3);
        if (is_numeric($id))
            if (! in_array($id,$this->userRepo->listAllUsers()->modelKeys()))
                abort(404);


        return $next($request);
    }
}
