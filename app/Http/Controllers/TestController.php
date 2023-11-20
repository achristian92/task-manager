<?php

namespace App\Http\Controllers;

use App\Mail\Activities\NotFinishedMail;
use App\Mail\SendCustomersLimitHours;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Customers\Customer;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    use ActivityTrait;

    private $userRepo;
    private IActivity $activityRepo;

    public function __construct(IUser $IUser, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $from = now()->startOfMonth()->format('Y-m-d');
        $to = now()->endOfMonth()->format('Y-m-d');

        $customers = Customer::whereNotNull('hours')->where('limit_hours',true)->get()
            ->filter(function ($customer) use ($from, $to) {
                $totalTime = convertMinutes($customer->getTotalTime($from, $to));
                $limitTime = convertMinutes($customer->hours);

                return $totalTime > $limitTime;
            })->transform(function ($customer) use ($from, $to) {
                $totalTime = $customer->getTotalTime($from, $to);

                return [
                    'name' => $customer->name,
                    'limit' => $customer->hours,
                    'time' => $totalTime,
                    'companyId' => $customer->company_id
                ];
            });

        $user = Auth::user();
        Mail::to("alan.ruiz@brainbox.pe")
            ->send(new SendCustomersLimitHours($user, $customers->ToArray()));

        dd("sending");
    }

    public function listUsers($company_id)
    {
        return User::with('roles')
            ->whereCompanyId($company_id)
            ->orderBy('name')
            ->get()
            ->filter(function (User $user){
                return $user->hasRole('Admin');
            });
    }


}
