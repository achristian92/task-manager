<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{


    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email',[
            'backLogin' => route('login'),
            'title' => 'Reinicio de contraseña',
            'passwordEmailRoute' => route('password.email'),
        ]);
    }

}
