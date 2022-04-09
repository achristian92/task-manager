<?php

namespace App\Http\Controllers;

use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\User;
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

        $date =  Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject('0.020833333333333'))->format('H:i');
        dd($date);


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
