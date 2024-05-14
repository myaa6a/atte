<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stamp;
use App\Models\Rest;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(105)->create();
        Rest::factory(5)->create();
        Stamp::factory(105)->create();
    }
}
