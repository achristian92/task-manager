<?php


namespace App\Repositories\Customers\Repository;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityTraitReport;
use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Prettus\Repository\Eloquent\BaseRepository;

class CustomerRepo extends BaseRepository implements ICustomer
{
    use UploadableTrait, DatesTrait, ActivityTraitReport;

    public function model(): string
    {
        return Customer::class;
    }

    public function findCustomerById(int $customer_id): Customer
    {
        return $this->model->findOrFail($customer_id);
    }

    public function createCustomer(array $data): Customer
    {
        return  $this->model->create($data);
    }

    public function updateCustomer(array $data, int $customer_id): bool
    {
        $customer = $this->findCustomerById($customer_id);
        return $customer->update($data);
    }

    public function deleteCustomer(int $customer_id): bool
    {
        $customer = $this->findCustomerById($customer_id);

        if (! $customer->activities()->exists()) {
            $customer->users()->detach();
            $customer->forceDelete();
            history(UserHistory::DELETE,"Eliminó al cliente $customer->name",$customer);
            return true;
        }

        history(UserHistory::DISABLE,"Desahilitado al cliente $customer->name",$customer);
        return $customer->update(['status' => false]);
    }

    public function listAllCustomers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::whereCompanyId(companyID())
            ->orderBy($orderBy,$sortBy)->get($columns);
    }

    public function listCustomers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::whereCompanyId(companyID())
            ->whereIsActive(true)
            ->orderBy($orderBy,$sortBy)
            ->get($columns);
    }

    public function historyHours(array $months, array $ids, $sub = 6)
    {
        $activities = Activity::with(['customer','sub_activities','partials'])
            ->whereCompanyId(companyID())
            ->whereIn('customer_id',$ids)
            ->whereDate('start_date','>=',$months['fromYmd'])
            ->whereDate('due_date','<=',$months['toYmd'])
            ->get()->transform(function ($activity) {
                return [
                    'custId'   => $activity->customer_id,
                    'custName' => $activity->customer->name,
                    'timeReal' => $activity->total_time_real, //compare between customers
                    'month'    => Carbon::parse($activity->start_date)->format('Y-m')
                ];
            });

        $dataset = $activities->groupBy('custName')->map(function ($activities, $tag) use ( $months ) {
            foreach ($months['formatYm'] as $month) {
                $bymonth = $activities->where('month',$month);
                list($hour, $minute) = explode(':', sumTime($bymonth,'timeReal'));
                $hours[] = $hour;
            }
            return [
                'seriesname' => Str::limit($tag, 15),
                'data' => self::transformDataSource($hours,'value'),
            ];
        })->values();

        return [
            'categories' => self::transformDataSource($months['names'],'label'),
            'dataset' => $dataset
        ];
    }
    private function transformDataSource(array $data, string $text)
    {
        return collect($data)->map(function ($name) use ($text){
            return [ $text => $name];
        });
    }

    public function reportTimeCustomerDays(string $from,string $to, array $rangedays)
    {
        return $this->activitiesAllReport($from, $to)
            ->groupBy('customer')
            ->map(function ($activities, $customer) use ($rangedays) {
                return [
                    'customer'  => $customer,
                    'total' => sumArraysTime($this->addTimeByDate($rangedays,$activities)),
                    'dates' => $this->addTimeByDate($rangedays,$activities)
                ];
            })->sortBy('customer');
    }

    public function reportUserWorked(int $id,string $from,string $to, $rangedays)
    {
        return $this->activitiesAllReport($from,$to,null,$id)
            ->groupBy('counter')
            ->map(function ($activities, $counter) use ($rangedays) {
                return [
                    'counter' => $counter,
                    'dates' => $this->addTimeByDate($rangedays,$activities),
                    'total' => sumTime(collect($activities),'realTime')
                ];
            })->sortBy('counter')->values();
    }

    public function reportHistory(string $from, string $to)
    {
        return Activity::with(['customer'])
            ->whereCompanyId(companyID())
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->get()
            ->transform(function ($activity) {
                return [
                    'customerName' => $activity->customer->name,
                    'totalRealTime' => $activity->total_time_real,
                    'startDateMonth' => Carbon::parse($activity->start_date)->format('Y-m')
                ];
            });
    }

    public function reportactivityByTag(int $id, string $from, string $to)
    {
        return $this->activitiesAllReport($from, $to,null,$id)
            ->groupBy('tag')
            ->map(function ($activities, $tag) {
                return [
                    'tag'                => $tag,
                    'totalEstimatedTime' => sumTime(collect($activities),'estimatedTime'),
                    'totalRealTime'      => sumTime(collect($activities),'realTime'),
                    'activities'         => $activities
                ];
            })->values();
    }
}
