<?php

namespace App\Console\Commands;

use App\Mail\Activities\NotFinishedMail;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Companies\Company;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ActivitiesNotFinished extends Command
{
    use ActivityTrait;

    protected $signature = 'notfinished:activities';


    protected $description = 'Notify activities not finished';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        self::queryActivities()
            ->groupBy('userName')->transform(function ($activities,$user) {
                $advance = $this->qtyCompleted($activities) + $this->qtyPartial($activities);
                $total   = $activities->count();
                return [
                    'name'           => $user,
                    'companyId'      => $activities[0]['companyId'],
                    'totalAct'       => $activities->count(),
                    'qtyCompleted'   => $advance,
                    'isCompletedAll' => (bool) ($total === $advance)
                ];
            })->filter(function ($activities) {
                return !$activities['isCompletedAll'];
            })->groupBy('companyId')->transform(function ($data,$company_id) {
                $company = Company::find($company_id);
                if ($company->notifyOverdueActivities() && !empty($data)) {
                    self::listUsers($company_id)->each(function ($user) use($data) {
                        Mail::to($user->email)
                            ->send(new NotFinishedMail($user, $data->ToArray()));
                    });
                }
            });

        return 0;
    }

    private function queryActivities()
    {
        return Activity::with('user','partials')
            ->whereIsPlanned(true)
            ->whereDate('deadline',Carbon::tomorrow()->format('Y-m-d'))
            ->orderBy('start_date','desc')
            ->get()
            ->transform(function (Activity $activity) {
                return [
                    'id'        => $activity->id,
                    'userID'    => $activity->user_id,
                    'userName'  => $activity->user->full_name,
                    'status'    => $activity->currentStatus(),
                    'companyId' => $activity->company_id
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
