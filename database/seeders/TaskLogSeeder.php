<?php

namespace Database\Seeders;

use App\Models\TaskLog;
use Illuminate\Database\Seeder;
use Database\Factories\TaskLogFactory;

class TaskLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskLog::factory()
            ->count(5)
            ->create();
    }
}
