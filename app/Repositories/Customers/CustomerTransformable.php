<?php

namespace App\Repositories\Customers;

use App\Repositories\Users\User;

trait CustomerTransformable
{
    public function trasformCustomerToSelect2(Customer $customer)
    {
        return [
            'id'   => $customer->id,
            'text' => $customer->name
        ];
    }

}
