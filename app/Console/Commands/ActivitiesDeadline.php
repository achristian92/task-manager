<?php

namespace App\Console\Commands;

use App\Mail\Activities\DeadlineUserMail;
use App\Repositories\Activities\Activity;
use App\Repositories\Companies\Company;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ActivitiesDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify activities deadline by user and admin';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        self::queryActivities()
            ->transform(function (Activity $activity) {
            return [
                'id'       => $activity->id,
                'activity' => Str::limit(strtoupper($activity->name),35),
                'time'     => $activity->time_estimate,
                'customer' => Str::limit(strtoupper($activity->customer->name),25),
                'deadline' => Carbon::parse($activity->deadline)->format('d/m'),
                'user'     => $activity->user,
                'userName' => $activity->user->full_name,
                'companyId'=> $activity->company_id
            ];
        })->groupBy('userName')->transform(function ($activities, $username) {
            return [
                'user'       => $activities[0]['user'],
                'deadline'   => $activities[0]['deadline'],
                'companyId'  => $activities[0]['companyId'],
                'totalAct'   => $activities->count(),
                'userName'   => $username,
                'activities' => $activities->toArray()
            ];
        })->groupBy('companyId')->transform(function ($data,$company_id) {
            $company = Company::find($company_id);

            if ($company->notifyDeadlineActivities() && !empty($data)) {
                // Notify user
                collect($data)->each(function ($data) {
                    Mail::to($data['user']->email)->send(new DeadlineUserMail($data));
                });

                // Notify admin and supervisor
//                    self::listUsers($company_id)->each(function ($user) use($data) {
//                        Mail::to($user->email)->send(new DeadlineAdminMail($user,$data->toArray()));
//                    });
            }
        });
        return 0;
    }

    private function queryActivities(): Collection
    {
        return Activity::with('user','customer')
            ->whereIsPlanned(true)
            ->whereDate('deadline',Carbon::tomorrow()->format('Y-m-d'))
            ->where('status','!=',Activity::TYPE_COMPLETED)
            ->orderBy('start_date','desc')
            ->get();
    }

    private function listUsers($company_id)
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
