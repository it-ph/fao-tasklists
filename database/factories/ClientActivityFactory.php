<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'agent_id' => 1506,
            'dashboard_activity_id' => 1,
            'name' => ucwords($this->faker->sentence())
        ];
    }
}
