<?php

namespace Database\Seeders;

use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Tag;
use App\Repositories\Users\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {
        Tag::factory(50)->create();
    }
}
