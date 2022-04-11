<?php

namespace App\Http\Controllers;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Customer;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestController extends Controller
{
    private $userRepo;
    private IActivity $activityRepo;

    public function __construct(IUser $IUser, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {

        $user = User::find(1);

        $activities = $this->activityRepo->queryActivitiesReport('2022-04-01','2022-04-30')
            ->where('userId',$user->id)
            ->groupBy('customer')
            ->transform(function ($activities, $customer) {
                dd($activities);
                return [
                    'customer'           => $customer,
                    'totalEstimatedTime' => sumTime(new DatabaseCollection($activities),'estimatedTime'),
                    'totalRealTime'      => sumTime(new DatabaseCollection($activities),'realTime'),
                    'activities'         => $activities
                ];
            })->values();

        dd($activities);


    }


    private function tagHours($fromMonth,int $sub = 6)
    {
        $startDate = '2022-03-01';
        $endDate = '2022-03-31';

        $from = Carbon::parse('2022-03-01')->subMonths(6)->format('Y-m-d');
        $to =  CarbonPeriod::create($from, '1 month', $endDate);

        $rangeMonth = [];
        $rangeMonthName = [];
        foreach ($to as $month) {
            $rangeMonth[] = $month->format("Y-m");
            $rangeMonthName[] = ucfirst($month->monthName);
        }


        $activities = Activity::with(['tag'])
            ->whereCompanyId(1)
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$endDate)
            ->get()->transform(function ($activity) {
                return [
                    'tagId'    => $activity->tag_id,
                    'tagName'  => $activity->tag->name,
                    'timeReal' => $activity->time_real,
                    'month'    => Carbon::parse($activity->start_date)->format('Y-m')
                ];
            });

       $dataset = $activities->groupBy('tagName')->map(function ($activities, $tag) use ( $rangeMonthName, $rangeMonth ) {
            foreach ($rangeMonth as $month) {
                list($hour, $minute) = explode(':', $this->sumTime($activities->where('month',$month),'timeReal'));
                $hours[] = $hour;
            }
            return [
                'seriesname' => Str::limit($tag, 15),
                'data' => $this->transformDataSource($hours,'value'),
            ];
        })->values();

        return [
            'categories' => $this->transformDataSource($rangeMonthName,'label'),
            'dataset' => $dataset
        ];
    }
    private function transformDataSource(array $data, string $text)
    {
        return collect($data)->map(function ($name) use ($text){
            return [ $text => $name];
        });
    }



}
