<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Admin','Supervisor','Usuario'];
        foreach ($roles as $rol) {
            DB::table('roles')->insert([
                'name'        => $rol,
                'guard_name'  => 'web',
            ]);
        }
    }
}
