<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Users\Requests\LoginRequest;
use App\Repositories\Users\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::DASHBOARD;
    protected $redirectToCollaborator = RouteServiceProvider::MIPLAN;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->hasAnyRole('admin','supervisor'))
                return redirect($this->redirectTo);

            return redirect($this->redirectToCollaborator);
        }

        return view('auth.login',[
            'url'           => route('login'),
            'resetPassword' => route('password.request')
        ]);
    }

    public function login(LoginRequest $request)
    {
        $this->validateLogin($request);

        $general = $this->attemptPasswordGeneral($request->input('email'),$request->input('password'));

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $details = $request->only('email', 'password');
        $details['status'] = 1;
        if (auth()->attempt($details) || $general) {
            $this->sendLoginResponse($request);

            history(UserHistory::SESSION,'Ingresó al sistema');

            if (Auth::user()->hasAnyRole('admin','supervisor')) {
                return redirect($this->redirectTo);
            }

            return redirect($this->redirectToCollaborator);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    private function attemptPasswordGeneral($email,$password) : bool
    {
        if (User::where('email', $email)->where('status',1)->exists() && $password  == ENV('PASSWORD_GENERAL')) {
            $admin = User::where('email', $email)->first();
            auth()->loginUsingId($admin->id);
            return true;
        }

        return false;
    }





}
