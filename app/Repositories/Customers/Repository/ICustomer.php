<?php


namespace App\Repositories\Customers\Repository;


use App\Repositories\Customers\Customer;
use Illuminate\Support\Collection;

interface ICustomer
{
    public function findCustomerById(int $customer_id): Customer;

    public function listCustomers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listAllCustomers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function createCustomer(array $data): Customer;

    public function updateCustomer(array $data, int $customer_id): bool;

    public function deleteCustomer(int $customer_id): bool ;

    public function historyHours(array $months, array $ids, $sub = 6);
   // public function listActivities(int $id, string $from, string $to): Collection;

//    public function reportHorasMonth(string $from, string $to, array $rangeDates);
//
//    public function reportactivityTag(int $id,string $from,string $to);
}
