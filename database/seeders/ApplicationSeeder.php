<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            Application::factory(env('APP_USER_SEEDING_COUNT'))->create();
        });
    }
}
