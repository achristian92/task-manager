<?php

namespace App\Console\Commands;

use App\Mail\SendCustomersLimitHours;
use App\Repositories\Customers\Customer;
use App\Repositories\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CustomersLimitHours extends Command
{
    protected $signature = 'limithours:customer';


    protected $description = 'Limit horas by customer';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        self::queryCustomersLimitHours()
            ->groupBy('companyId')->transform(function ($data,$company_id) {
                self::listUsers($company_id)->each(function ($user) use($data) {
                    \Log::info("Notify limit hours:". $user->email);
                    Mail::to($user->email)
                        ->send(new SendCustomersLimitHours($user, $data->toArray()));
                });
            });

        return 0;
    }

    private function queryCustomersLimitHours()
    {
        $from = now()->startOfMonth()->format('Y-m-d');
        $to = now()->endOfMonth()->format('Y-m-d');

        return Customer::whereNotNull('hours')->where('limit_notify',true)->get()
            ->filter(function ($customer) use ($from, $to) {
                $totalTime = convertMinutes($customer->getTotalTime($from, $to));
                $limitTime = convertMinutes($customer->hours);

                return $totalTime > $limitTime;
            })->transform(function ($customer) use ($from, $to) {
                $totalTime = $customer->getTotalTime($from, $to);
                \Log::info("Con dataaa");
                return [
                    'name' => $customer->name,
                    'limit' => $customer->hours,
                    'time' => $totalTime,
                    'companyId' => $customer->company_id
                ];
            });
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
