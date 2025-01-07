<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\PerformanceEvaluationPeriod;

class ProbationaryPerformancePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = now();
        $end = fake()->dateTimeBetween($start, (clone $start)->modify('+10 days'));

        $validPeriods = array_filter(
            PerformanceEvaluationPeriod::cases(),
            fn($case) => $case->value !== 'annual'
        );

        $periods = array_map(function ($period) {
            return $period->value;
        }, $validPeriods);

        $values = [];

        foreach ($periods as $period) {
            array_push($values, [
                'period_name' => $period,
                'start_date' => $start,
                'end_date' => $end,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('probationary_performance_periods')->insert($values);
    }
}
