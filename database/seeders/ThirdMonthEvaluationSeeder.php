<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\PerformanceEvaluationPeriod;

class ThirdMonthEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = now();
        $end = fake()->dateTimeBetween($start, (clone $start)->modify('+10 days'));

        $probationaries = Employee::whereHas('status', function ($query) {
            $query->where('emp_status_name', EmploymentStatus::PROBATIONARY->label());
        })->get();

        $data = [];

        $probationaries->each(function ($item) 
            use (&$data, $start, $end) {
                array_push($data, [
                    'evaluatee' => $item->employee_id,
                    'period_name' => PerformanceEvaluationPeriod::THIRD_MONTH,
                    'start_date' => $start,
                    'end_date' => $end,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        );

        DB::table('probationary_performance_periods')->insert($data);
    }
}
