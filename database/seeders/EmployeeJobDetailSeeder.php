<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\JobFamily;
use App\Models\SpecificArea;
use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;
use App\Models\EmployeeJobDetail;

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
                'job_level_id' => JobLevel::inRandomOrder()->first()->job_level_id,
                'job_family_id' => JobFamily::inRandomOrder()->first()->job_family_id,
                'area_id' => SpecificArea::inRandomOrder()->first()->area_id,
                'shift_id' => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => EmploymentStatus::inRandomOrder()->first()->emp_status_id,
            ]);            
        });
    }
}
