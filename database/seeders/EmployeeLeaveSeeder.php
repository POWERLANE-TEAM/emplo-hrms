<?php

namespace Database\Seeders;

use App\Models\EmployeeLeave;
use Illuminate\Database\Seeder;

class EmployeeLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            EmployeeLeave::factory(50)->create();
        });
    }
}
