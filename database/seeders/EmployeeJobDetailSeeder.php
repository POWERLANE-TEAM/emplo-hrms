<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\SpecificArea;
use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;
use Illuminate\Support\Facades\DB;

class EmployeeJobDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all()->map(function ($item) {
            return [
                'employee_id' => $item->employee_id,
                'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
                'area_id' => SpecificArea::inRandomOrder()->first()->area_id,
                'shift_id' => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => EmploymentStatus::inRandomOrder()->first()->emp_status_id,
            ];
        })->toArray();

        DB::table('employee_job_details')->insert($employees);
    }
}
