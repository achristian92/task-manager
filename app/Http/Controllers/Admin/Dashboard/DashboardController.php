<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tools\DatesTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use DatesTrait;

    private $customerRepo, $activityRepo, $tagRepo;

    public function __construct(ICustomer $ICustomer,IActivity $IActivity,ITag $ITag)
    {
        $this->customerRepo = $ICustomer;
        $this->activityRepo = $IActivity;
        $this->tagRepo = $ITag;
    }

    public function __invoke(Request $request)
    {
        $qtyShow = $request->input('qtyShow',5);

        $date = $this->getDateFormats($request->input('yearAndMonth'));

        $activities = $this->activityRepo->listActivitiesToDashboard($date['from'],$date['to']);

        $customersHours = $this->totalHours($activities,'customer',$qtyShow);
        $usersHours = $this->totalHours($activities,'user',$qtyShow);

        return view('admin.dashboard.index',[
            'progress'      => $this->activityRepo->progress($activities),
            'resume'        => $this->activityRepo->resume($activities),
            'custMoreHours' => $customersHours['moreHours'],
            'custLessHours' => $customersHours['lessHours'],
            'usuMoreHours'  => $usersHours['moreHours'],
            'usuLessHours'  => $usersHours['lessHours'],
            'tagPercentage' => $this->tagRepo->percentage($activities),
            'tagHistory'    => $this->tagRepo->historyHours($this->subMonths($date['from'])),
            'customers'     => $this->customerRepo->listCustomers('name','asc',['id','name'])
        ]);
    }

    private function totalHours(Collection $activities,string $groupby, int $qtyShow = 5): array
    {
        $group = $groupby === 'customer' ? 'custName' : 'usuName';
        $route = $groupby === 'customer' ? 'admin.customers.show' : 'admin.tracks.show';
        $groupid = $groupby === 'customer' ? 'custId' : 'usuId';
        $total = $activities->groupBy($group)
            ->map(function ($activities, $custName) use ( $route , $groupid ) {
                list($hour, $minute) = explode(':', sumTime($activities,'timeReal'));
                return [
                    'label'     => $custName,
                    "labelLink" => route($route,$activities->first()[$groupid]),
                    'value'     => $hour
                ];
            })->sortBy('value')->values();

        return [
            'moreHours' => $total->take(-$qtyShow)->sortByDesc('value')->values(),
            'lessHours' => $total->take($qtyShow)
        ];
    }

}
