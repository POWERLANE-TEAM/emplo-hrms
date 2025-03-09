<?php

namespace Database\Seeders;

use App\Enums\EmploymentStatus;
use App\Enums\PerformanceEvaluationPeriod;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThirdMonthEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $probationaries = Employee::ofEmploymentStatus(EmploymentStatus::PROBATIONARY)->get();

        $data = [];

        $start = now()->subWeek();
        $end = $start->copy()->addWeeks(2);

        $probationaries->each(function ($proby) use (&$data, $start, $end) {
            $data[] = [
                'evaluatee' => $proby->employee_id,
                'period_name' => PerformanceEvaluationPeriod::THIRD_MONTH->value,
                'start_date' => $start,
                'end_date' => $end,
                'created_at' => $start,
                'updated_at' => $start,
            ];
        });

        DB::table('probationary_performance_periods')->insert($data);
    }
}
