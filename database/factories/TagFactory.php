<?php

namespace Database\Factories;

use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        return [
            'name'       => $this->faker->name(),
            'company_id' => $this->faker->numberBetween(1, 3)
        ];
    }
}
