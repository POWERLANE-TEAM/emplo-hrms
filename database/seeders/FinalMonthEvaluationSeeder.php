<?php

namespace Database\Seeders;

use App\Enums\EmploymentStatus;
use App\Enums\PerformanceEvaluationPeriod;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinalMonthEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = now();
        $end = $start->copy()->addWeeks(2);

        $probationaries = Employee::ofEmploymentStatus(EmploymentStatus::PROBATIONARY)->get();

        $data = [];

        $probationaries->each(function ($item) use (&$data, $start, $end) {
            array_push($data, [
                'evaluatee' => $item->employee_id,
                'period_name' => PerformanceEvaluationPeriod::FINAL_MONTH,
                'start_date' => $start,
                'end_date' => $end,
                'created_at' => $start,
                'updated_at' => $start,
            ]);
        }
        );

        DB::table('probationary_performance_periods')->insert($data);
    }
}
