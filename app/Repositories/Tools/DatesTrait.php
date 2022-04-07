<?php


namespace App\Repositories\Tools;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

trait DatesTrait
{
    public function getDateFormats(string $yy_mm = null): array
    {
        $dateFormat = Carbon::createFromDate(); // current date
        if ($yy_mm)
            $dateFormat = Carbon::createFromDate($yy_mm);

        $startOfMonth = Carbon::parse($dateFormat)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::parse($dateFormat)->endOfMonth()->format('Y-m-d');

        return [
          'from' => $startOfMonth,
          'to'   => $endOfMonth
        ];

    }

    public function subMonths($fromMonth ,$sub = 6)
    {
        $from = Carbon::createFromDate($fromMonth)->startOfMonth();
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
    }

}
