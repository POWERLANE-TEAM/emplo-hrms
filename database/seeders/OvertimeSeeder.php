<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalActiveEmployees = Employee::activeEmploymentStatus()->count();

        activity()->withoutLogs(function () use ($totalActiveEmployees) {
            Overtime::unguard();
            Overtime::factory($totalActiveEmployees)->create();
            Overtime::reguard();
        });
    }
}   
