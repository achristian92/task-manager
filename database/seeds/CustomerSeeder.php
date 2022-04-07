<?php

namespace Database\Seeders;

use App\Repositories\Customers\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{

    public function run()
    {
       Customer::factory(1000)->create();

    }
}
