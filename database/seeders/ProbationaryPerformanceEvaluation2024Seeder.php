<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use App\Models\PerformanceRating;
use Illuminate\Support\Facades\DB;
use App\Models\PerformanceCategory;
use App\Enums\PerformanceEvaluationPeriod;

class ProbationaryPerformanceEvaluation2024Seeder extends Seeder
{
    private $probationaries;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->probationaries = Employee::whereHas('status', function ($query) {
            $query->where('emp_status_name', EmploymentStatus::PROBATIONARY->label());
        })->get();

        $this->seedEvaluations(PerformanceEvaluationPeriod::THIRD_MONTH->value, Carbon::create(2024, 1, 1));
        $this->seedEvaluations(PerformanceEvaluationPeriod::FIFTH_MONTH->value, Carbon::create(2024, 5, 1));
        $this->seedEvaluations(PerformanceEvaluationPeriod::FINAL_MONTH->value, Carbon::create(2024, 9, 1));
    }

    /**
     * Seed evaluations for a specific period.
     *
     * @param string $periodName
     * @param \Carbon\Carbon $start
     */
    private function seedEvaluations(string $periodName, \Carbon\Carbon $start): void
    {
        $end = (clone $start)->addMonth()->endOfMonth();
        $performanceData = [];
        $ratingData = [];
        $categories = PerformanceCategory::all();
        $ratings = PerformanceRating::all();

        $this->probationaries->each(function ($employee) 
            use (&$performanceData, &$ratingData, $start, $end, $periodName, $categories, $ratings) {
            $periodId = DB::table('probationary_performance_periods')->insertGetId([
                'evaluatee' => $employee->employee_id,
                'period_name' => $periodName,
                'start_date' => $start,
                'end_date' => $end,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'period_id');

            $performanceId = DB::table('probationary_performances')->insertGetId([
                'period_id' => $periodId,
                'evaluatee_comments' => fake()->paragraph(),
                'evaluatee_signed_at' => fake()->optional()->dateTimeBetween($start, $end),
                'evaluator_comments' => fake()->paragraph(),
                'evaluator_signed_at' => fake()->dateTimeBetween($start, $end),
                'secondary_approver_signed_at' => fake()->dateTimeBetween($start, $end),
                'third_approver_signed_at' => fake()->dateTimeBetween($start, $end),
                'fourth_approver_signed_at' => fake()->dateTimeBetween($start, $end),
                'is_final_recommend' => fake()->boolean(),
                'is_employee_acknowledged' => fake()->boolean(),
                'evaluator' => Employee::inRandomOrder()->first()->employee_id,
                'secondary_approver' => Employee::inRandomOrder()->first()->employee_id,
                'third_approver' => Employee::inRandomOrder()->first()->employee_id,
                'fourth_approver' => Employee::inRandomOrder()->first()->employee_id,
            ], 'probationary_performance_id');

            foreach ($categories as $category) {
                $rating = $ratings->random();
                $ratingData[] = [
                    'perf_category_id' => $category->perf_category_id,
                    'perf_rating_id' => $rating->perf_rating_id,
                    'probationary_performance_id' => $performanceId,
                ];
            }
        });

        DB::table('probationary_performance_ratings')->insert($ratingData);
    }
}
