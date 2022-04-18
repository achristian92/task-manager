<?php


namespace App\Repositories\Users\Transformations;


use App\Mail\SendEmailNewUser;
use App\Repositories\Users\User;
use Illuminate\Support\Facades\Mail;

trait UserTransformable
{
    public function trasformUserToSelect2(User $user)
    {
        return [
            'id'   => $user->id,
            'text' => $user->full_name
        ];
    }

}
