<?php


namespace App\Repositories\Customers\Repository;

use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Tools\UploadableTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Prettus\Repository\Eloquent\BaseRepository;

class CustomerRepo extends BaseRepository implements ICustomer
{
    use UploadableTrait, DatesTrait, TActivityReport;

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
                    'custId'    => $activity->customer_id,
                    'custName'  => $activity->customer->name,
                    'timeReal' => $activity->totalTimeEntered($activity['sub_activities'],$activity['partials']), //compare between customers
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
}
