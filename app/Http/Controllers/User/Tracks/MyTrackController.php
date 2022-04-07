<?php

namespace App\Http\Controllers\User\Tracks;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MyTrackController extends Controller
{
    use ActivityTrait, ActivityTransformable, DatesTrait;

    private $userRepo, $activityRepo;

    public function __construct(IUser $IUser,IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $user_id = Auth::id();

        $date = $this->getDateFormats(request()->input('yearAndMonth'));

        $activities = $this->activityRepo->listActivityByUser($user_id,$date['from'],$date['to'])
            ->transform(function ($activity) {
            return $this->transformActivityAdvance($activity);
        });
        $arrayEstimatedTime = Arr::pluck($activities,'estimatedTime');
        $arrayDuration = Arr::pluck($activities,'realTime');

        return view('user.tracks.index',[
            'user'       => User::find($user_id)->full_name,
            'timeWorked' => sumArraysTime($arrayEstimatedTime),
            'timeReal'   => sumArraysTime($arrayDuration),
            'progress'   => $this->activityRepo->progress($activities),
            'resume'     => $this->activityRepo->resume($activities),
            'activities' => $activities,
        ]);
    }
}
