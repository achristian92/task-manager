<?php

namespace App\Http\Controllers;

use App\Mail\Activities\DeadlineAdminMail;
use App\Mail\Activities\DeadlineUserMail;
use App\Mail\Activities\EvaluationMail;
use App\Mail\Activities\NotFinishedMail;
use App\Mail\SendEmailActivitiesDeadline;
use App\Mail\SendEmailActivitiesDeadlineAdmin;
use App\Mail\SendEmailAlertActivitiesNotLoaded;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Companies\Company;
use App\Repositories\Customers\Customer;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $activities = Activity::with('user')
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
            })->groupBy('userName')->transform(function ($activities, $user) {
                return [
                    'user' => $user,
                    'companyId' => $activities[0]['companyId'],
                    'qty'  => $activities->count(),
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

        dd($activities);
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

    public function totalrealtime()
    {
        Activity::orderBy('start_date','desc')->get()
            ->each(function ($activity) {
                $activity->total_time_real = $activity->time_real;
                $activity->save();
            });
    }


}
