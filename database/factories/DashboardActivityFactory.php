<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DashboardActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucwords($this->faker->sentence())
        ];
    }
}
