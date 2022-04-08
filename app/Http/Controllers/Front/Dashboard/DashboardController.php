<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tools\DatesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    use ActivityTransformable,ActivityTrait, DatesTrait;

    private $activityRepo;
    private ICustomer $customerRepo;

    public function __construct(IActivity $IActivity,ICustomer $ICustomer)
    {
        $this->activityRepo = $IActivity;
        $this->customerRepo = $ICustomer;
    }

    public function __invoke(Request $request)
    {
        $ids = $request->input('customer_ids',[]);
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        $activities =  $this->activityRepo->listActivitiesToDashboard($date['from'],$date['to']);

        $filtered = $activities->whereIn('custId',$ids);

        return response()->json([
            'hours'             => $this->hours($filtered),
            'activities'        => $this->activities($filtered),
            'period'            => $this->customerRepo->historyHours($this->subMonths($date['from']),$ids)
        ]);
    }

    private function hours(Collection $activities):array
    {
        $calculateActivities = $activities->groupBy('custName')->map(function ($activities, $customer) {
            return [
                'customer' => Str::limit($customer,15),
                'estimatedHours' => sumTime($activities,'timeEstimated'),
                'realHours' => sumTime($activities,'timeReal')
            ];
        })->values();

        return [
            'categories' => $this->transformDataSource($calculateActivities->pluck('customer')->toArray(),'label'),
            'dataset' => [
                [
                    'seriesname'=> "Estimado",
                    'data'=> $this->transformDataSource($calculateActivities->pluck('estimatedHours')->toArray(),'value'),
                ],
                [
                    'seriesname'=> "Real",
                    'data'=> $this->transformDataSource($calculateActivities->pluck('realHours')->toArray(),'value'),
                ]
            ]
        ];
    }

    private function activities(Collection $activities)
    {
        $calculateActivities = $activities->groupBy('custName')->map(function ($activities, $customer) {
            return [
                'customer' => Str::limit($customer,15),
                'totalAct' => $activities->count(),
                'completedAct' => $this->qtyCompleted($activities)
            ];
        })->values();

        return [
            'categories' => $this->transformDataSource($calculateActivities->pluck('customer')->toArray(),'label'),
            'dataset' => [
                [
                    'seriesname' => "Total",
                    'data' => $this->transformDataSource($calculateActivities->pluck('totalAct')->toArray(),'value'),
                ],
                [
                    'seriesname' => "Completados",
                    'data'=> $this->transformDataSource($calculateActivities->pluck('completedAct')->toArray(),'value'),
                ]
            ]
        ];
    }

    private function history()
    {

    }
    private function transformDataSource(array $data, string $labelorvalue)
    {
        return collect($data)->map(function ($name) use ($labelorvalue){
            return [ $labelorvalue => $name];
        });
    }
}
