<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeJobDetail;
use App\Models\EmploymentStatus;
use App\Models\JobTitle;
use App\Models\Shift;
use App\Models\SpecificArea;
use Illuminate\Database\Seeder;

class EmployeeJobDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            EmployeeJobDetail::create([
                'employee_id' => Employee::inRandomOrder()->first()->employee_id,
                'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
                'area_id' => SpecificArea::inRandomOrder()->first()->area_id,
                'shift_id' => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => EmploymentStatus::inRandomOrder()->first()->emp_status_id,
            ]);
        });
    }
}