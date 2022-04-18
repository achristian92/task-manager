<?php

namespace App\Http\Controllers;

use App\Mail\Activities\DeadlineAdminMail;
use App\Mail\Activities\DeadlineUserMail;
use App\Mail\Activities\EvaluationMail;
use App\Mail\Activities\NotFinishedMail;
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
        $users = User::all()->map(function ($user) {
            $new_email = Str::replace('jga.pe', 'test.com', $user->email);
            $user->update(['email' => $new_email]);
        });
        dump("end");
    }


}
