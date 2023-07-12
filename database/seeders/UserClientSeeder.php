<?php

namespace Database\Seeders;

use App\Models\UserClient;
use Illuminate\Database\Seeder;

class UserClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserClient::factory()
            ->count(25)
            ->create();
    }
}
