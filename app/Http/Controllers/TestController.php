<?php

namespace App\Http\Controllers;

use App\Mail\Activities\NotFinishedMail;
use App\Mail\SendCustomersLimitHours;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Tag;
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
        $from = request('issue_date');
        $to = request('to');
        if(!$from)
            return false;

        $companyFront = 1;
        $companyTo = 2;

        $customers = Customer::where('company_id',$companyTo)->get();
        $users = User::where('company_id',$companyTo)->get();
        $tags = Tag::where('company_id',$companyTo)->get();

        Activity::where('company_id',$companyFront)->where('created_at','>=',$from)->where('created_at','<=',$to)->get()
            ->each(function ($activity) use ($companyTo,$customers,$users,$tags) {
                $userCreated = $users->random()->id;

                $newActivity = $activity->replicate();

                if($activity->updated_by_id)
                    $newActivity->updated_by_id = $users->random()->id;

                if($activity->approved_by_id)
                    $newActivity->approved_by_id = $users->random()->id;

                if($activity->approved_change_date_by)
                    $newActivity->approved_change_date_by = $users->random()->id;

                $newActivity->tag_id        = $tags->random()->id;
                $newActivity->customer_id   = $customers->random()->id;

                $newActivity->created_by_id = $userCreated;
                $newActivity->user_id       = $userCreated;
                $newActivity->company_id    = $companyTo;
                $newActivity->save();
            });

        return ("Fin");
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
