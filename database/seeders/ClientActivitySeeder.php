<?php

namespace Database\Seeders;

use App\Models\ClientActivity;
use Illuminate\Database\Seeder;

class ClientActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientActivity::factory()
            ->count(25)
            ->create();
    }
}
