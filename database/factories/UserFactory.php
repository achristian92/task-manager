<?php

namespace Database\Factories;

use App\Repositories\Tags\Tag;
use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'                    => $this->faker->name(),
            'email'                   => $this->faker->unique()->safeEmail(),
            'email_verified_at'       => now(),
            'password'                => bcrypt('password'), // password
            'raw_password'            => 'password',
            'remember_token'          => Str::random(10),
            'can_check_all_customers' => $this->faker->numberBetween(0,1),
            'can_be_check_all'        => $this->faker->numberBetween(0,1),
            'company_id'              => $this->faker->numberBetween(1, 3)
        ];
    }
}
