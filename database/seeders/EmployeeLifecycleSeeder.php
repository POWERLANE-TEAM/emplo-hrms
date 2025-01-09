<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeLifecycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::with('status')->get();

        $data = [];

        $employees->each(function ($item) use (&$data) {    

            $starting = $item->status->emp_status_name === EmploymentStatus::PROBATIONARY->label()
                ? fake()->dateTimeBetween('-6 months', '-1 month')
                : fake()->dateTimeBetween('-5 years', '-1 year');

            $separated = ! in_array($item->status->emp_status_name, [
                EmploymentStatus::REGULAR->label(),
                EmploymentStatus::PROBATIONARY->label(),
            ]);
        
            array_push($data, [
                'employee_id' => $item->employee_id,
                'started_at' => $starting,
                'created_at' => now(),
                'separated_at' => $separated === true ? now() : null,
                'updated_at' => now(),
            ]);
        });

        DB::table('employee_lifecycles')->insert($data);
    }
}
