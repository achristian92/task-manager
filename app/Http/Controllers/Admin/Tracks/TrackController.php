<?php

namespace App\Http\Controllers\Admin\Tracks;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Companies\Company;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use Illuminate\Support\Str;

class TrackController extends Controller
{
    use ActivityTrait, ActivityTransformable, DatesTrait;

    private $activityRepo, $userRepo;

    public function __construct(IUser $IUser,IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }
    public function index()
    {
        $user = \Auth::user();

        $dateFormat = $this->getDateFormats(request()->input('yearAndMonth'));
        $setting = Company::find($user->company_id);
        $usersCanSeeIds = $this->userRepo->listUsersCanSee()->pluck('id');
        $activities = $this->activityRepo->listActivities($dateFormat['from'],$dateFormat['to'])
            ->transform(function ($activity) {
                return $this->transformActivityAdvance($activity);
            });


        $tracks =  $activities->whereIn('userId',$usersCanSeeIds)
            ->groupBy('userName','userId')
            ->map(function ($activities,$counter) use ($setting) {
                $progress = $this->activityRepo->progress($activities);
                list($hours, $minute) = explode(':', sumTime($activities,'realTime'));
                $qtys = $this->activityRepo->resume($activities);

                return [
                    'id'            => $activities->first()['userId'],
                    'name'          => Str::limit($counter,30),
                    'qtyOverdue'    => $qtys['qtyOverdue'],
                    'qtyCompleted'  => $qtys['qtyCompleted'],
                    'total'         => $qtys['total'],
                    'estimatedTime' => sumTime($activities,'estimatedTime'),
                    'realTime'      => sumTime($activities,'realTime'),
                    'progress'      => $progress,
                    'bgProgress'    => _bgProgress($progress),
                    'hoursWorked'   => intval($hours),
                    'performance'   => $hours == 0 ? 0 : number_format((($hours * 100)/$setting->hours),2),
                    'performanceRaw'=> intval($hours)
                ];
            })->sortBy('name')->values();

        return view('admin.tracks.index',[
            'tracks'     => $tracks,
            'hoursMonth' => intval($setting->hours),
        ]);
    }
}
