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
        $frequency = $this->faker->randomElement(['Daily', 'Weekly', 'Monthly', 'Quarterly', 'Annual']);
        $status = $this->faker->randomElement(['Not Started', 'In Progress - Without Issue', 'In Progress - With Issue', 'Completed']);

        return [
            'task_number' => $this->faker->randomNumber(5, true),
            'cluster_id' => 1,
            'client_id' => 1,
            'agent_id' => 1507,
            'accounting_period' => $this->faker->monthName() . $this->faker->year(),
            'dashboard_activity_id' => DashboardActivity::factory(),
            'client_activity_id' => ClientActivity::factory(),
            'client_detailed_activity' => $this->faker->sentence(2),
            'prerequisite_dependency' => $this->faker->sentence(),
            'poc' => $this->faker->name(),
            'go_live_date' => $this->faker->dateTime(),
            'frequency' => $frequency,
            'due_date' => $this->faker->word(),
            'estimated_handling_time' => $this->faker->sentence(),
            'status' => $status,
            'status_date' => $this->faker->dateTime(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'actual_handling_time' => $this->faker->time('H_i_s'),
            'volume' => $this->faker->numberBetween(0, 100),
            'remarks' => $this->faker->sentence(),
            'dtp_link' => $this->faker->url(),
            'training_recording_link' => $this->faker->url(),
            'created_by' => 1507,
        ];
    }
}
