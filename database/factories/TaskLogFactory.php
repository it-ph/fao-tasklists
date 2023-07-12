<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Tasks;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'task_id' => 2,
            'activity' => $this->faker->sentence(),
            'created_by' => 1507,
        ];
    }
}
