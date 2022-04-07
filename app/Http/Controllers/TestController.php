<?php

namespace App\Http\Controllers;

use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Users\Repository\IUser;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestController extends Controller
{
    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        $from = Carbon::createFromDate('2022-03')->startOfMonth();
        $end = $from->endOfMonth()->format('Y-m-d');
        $substart = $from->subMonths(6)->format('Y-m-d');
        $period = CarbonPeriod::create($substart, '1 month', $end);
        $rangeMonth = [];
        $rangeMonthName = [];
        foreach ($period as $month) {
            $rangeMonth[] = $month->format("Y-m");
            $rangeMonthName[] = ucfirst($month->monthName);
        }


        return [
            'fromYmd'  => $substart,
            'toYmd'    =>  $end,
            'formatYm' => $rangeMonth,
            'names'    => $rangeMonthName
        ];


        $company_id = 1;
        $startDate = '2021-03-02';
        $endDate = '2022-03-31';

       $activities = Activity::with(['customer','user','tag'])
           ->whereCompanyId($company_id)
           ->whereDate('start_date','>=',$startDate)
           ->whereDate('due_date','<=',$endDate)
           ->get()->transform(function ($activity) {
                return [
                    'status'   => $activity->currentStatus(),
                    'custId'   => $activity->customer_id,
                    'custName' => $activity->customer->name,
                    'usuId'   => $activity->user_id,
                    'usuName' => $activity->user->full_name,
                    'tagId'   => $activity->tag_id,
                    'tagName' => $activity->tag->name,
                    'timeReal' => $activity->time_real,
                ];
           });


       $taghours = $this->tagHours($activities);

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
