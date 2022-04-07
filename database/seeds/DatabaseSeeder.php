<?php

namespace Database\Seeders;

use App\Repositories\Users\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CompanySeeder::class,
            JgaSeeder::class
//            UserSeeder::class,
//            CustomerSeeder::class,
//            TagSeeder::class
        ]);
    }
}
