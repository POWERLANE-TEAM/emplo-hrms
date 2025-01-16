<?php

namespace Database\Seeders;

use App\Enums\PerformanceEvaluationPeriod;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use App\Models\PerformanceRating;
use App\Models\RegularPerformance;
use App\Models\PerformanceCategory;
use App\Models\RegularPerformancePeriod;
use App\Models\RegularPerformanceRating;

class RegularPerformanceEvaluation2024Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $performancePeriod = RegularPerformancePeriod::create([
            'period_name' => PerformanceEvaluationPeriod::ANNUAL->value,
            'start_date' => Carbon::create(2024, 1, 1, 0, 0, 0),
            'end_date' => Carbon::create(2024, 12, 31, 23, 59, 59),
        ]);

        $regularEmployees = Employee::whereHas('status', function ($query) {
            $query->where('emp_status_name', EmploymentStatus::REGULAR->label());
        })->get();

        foreach ($regularEmployees as $employee) {
            $regularPerformance = RegularPerformance::create([
                'period_id' => $performancePeriod->period_id,
                'evaluatee' => $employee->employee_id,
                'evaluatee_comments' => fake()->paragraph(),
                'evaluator_comments' => fake()->paragraph(),
                'is_employee_acknowledged' => true,
            ]);

            $this->seedRegularPerformanceRatings($regularPerformance, $employee);
        }
    }

    /**
     * Seed the regular performance ratings for a given performance record.
     */
    private function seedRegularPerformanceRatings(RegularPerformance $regularPerformance, Employee $employee): void
    {
        $categories = PerformanceCategory::all();
        $ratings = PerformanceRating::all();

        foreach ($categories as $category) {;
            $rating = $ratings->random();

            RegularPerformanceRating::create([
                'perf_category_id' => $category->perf_category_id,
                'perf_rating_id' => $rating->perf_rating_id,
                'regular_performance_id' => $regularPerformance->regular_performance_id,
            ]);
        }
    }
}