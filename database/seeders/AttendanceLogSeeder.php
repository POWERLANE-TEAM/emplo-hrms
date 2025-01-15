<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Database\Seeder;

class AttendanceLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttendanceLog::factory(Employee::count() * 5)->create();
    }
}
