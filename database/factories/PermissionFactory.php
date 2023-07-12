<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Cluster;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $permission = $this->faker->randomElement(['Accountant', 'Team Lead', 'Operations Manager']);

        return [
            'user_id' => 1507,
            'cluster_id' => Cluster::factory(),
            'client_id' => Client::factory(),
            'tl_id' => 1507,
            'om_id' => 1507,
            'permission' => $permission
        ];
    }
}
