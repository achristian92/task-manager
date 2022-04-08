<?php


namespace App\Repositories\Customers\Repository;

use App\Repositories\Customers\Customer;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Tools\UploadableTrait;
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

}
