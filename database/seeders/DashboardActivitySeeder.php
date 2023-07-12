<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DashboardActivity;

class DashboardActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DashboardActivity::factory()
            ->count(25)
            ->create();
    }
}
