<?php

namespace Database\Seeders;

use App\Repositories\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->insert([
            'name'           => 'Admin',
            'email'          => 'aruiz@tavera.pe',
            'password'       => bcrypt('123456'),
            'raw_password'   => '123456',
            'created_at'     => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'     => \Carbon\Carbon::now()->toDateTimeString(),
            'nro_document'   => '12395147',
            'can_check_all_customers' => 1,
            'can_be_check_all'        => 1,
            'company_id'              => 1
        ]);

        // Create the initial admin user
        $roles = [1,2,3];
        foreach ($roles as $role) {
            DB::table('model_has_roles')->insert([
                'role_id'    => $role,
                'model_type' => 'App\Repositories\Users\User',
                'model_id'   => '1',
            ]);
        }

        User::factory(50)->create()
            ->each(function ($user) {
                $num = rand(1, 3);
                for ($i = 1; $i <= $num; $i++) {
                    DB::table('model_has_roles')->insert([
                        'role_id'    => $i,
                        'model_type' => 'App\Repositories\Users\User',
                        'model_id'   => $user->id,
                    ]);
                }
            });
    }
}
