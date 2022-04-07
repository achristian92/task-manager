<?php

namespace Database\Factories;

use App\Repositories\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;


class CustomerFactory extends Factory
{

    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->company,
            'address'           => $this->faker->address,
            'hours'             => $this->faker->numberBetween($min = 100, $max = 300),
            'ruc'               => $this->faker->e164PhoneNumber,
            'contact_name'      => $this->faker->userName,
            'contact_telephone' => $this->faker->phoneNumber,
            'contact_email'     => $this->faker->companyEmail,
            'company_id'        => $this->faker->numberBetween(1, 3)
        ];
    }
}
