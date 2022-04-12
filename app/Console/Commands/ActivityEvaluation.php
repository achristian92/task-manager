<?php

namespace App\Console\Commands;

use App\Mail\Activities\EvaluationMail;
use App\Mail\Activities\NotFinishedMail;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Companies\Company;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ActivityEvaluation extends Command
{
    use ActivityTrait;

    protected $signature = 'evaluation:activities';


    protected $description = 'Notify activities by evaluation';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        self::queryActivities()
            ->groupBy('userName')->transform(function ($activities, $user) {
            return [
                'user'      => $user,
                'companyId' => $activities[0]['companyId'],
                'qty'       => $activities->count(),
            ];
        })->groupBy('companyId')->transform(function ($data, $company_id) {
            $company = Company::find($company_id);
            if (!empty($data)) {
                self::listUsers($company_id)->each(function ($user) use($data) {
                    Mail::to($user->email)
                        ->send(new EvaluationMail($user, $data->ToArray()));
                });
            }
        });


        return 0;
    }

    private function queryActivities()
    {
        return Activity::with('user')
            ->whereDate('start_date',Carbon::yesterday()->format('Y-m-d'))
            ->where('different_completed_date',true)
            ->whereNull('approved_change_date_by')
            ->latest()
            ->get()
            ->transform(function (Activity $activity) {
                return [
                    'userName' => $activity->user->full_name,
                    'companyId' => $activity->company_id,
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
