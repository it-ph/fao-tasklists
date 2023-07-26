<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Cluster;
use App\Models\Permission;
use App\Models\ClientActivity;
use App\Models\DashboardActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['In Progress', 'Completed']);

        return [
            'task_number' => $this->faker->randomNumber(5, true),
            'cluster_id' => 1,
            'client_id' => 1,
            'agent_id' => 1507,
            'date_received' => $this->faker->monthName() . $this->faker->year(),
            'dashboard_activity_id' => DashboardActivity::factory(),
            'client_activity_id' => ClientActivity::factory(),
            'description' => $this->faker->sentence(2),
            'status' => $status,
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'actual_handling_time' => $this->faker->time('H_i_s'),
            'volume' => $this->faker->numberBetween(0, 100),
            'remarks' => $this->faker->sentence(),
            'created_by' => 1507,
        ];
    }
}
