<?php

namespace Database\Seeders;

use App\Enums\EmploymentStatus;
use App\Models\Employee;
use App\Models\PerformanceCategory;
use App\Models\RegularPerformance;
use App\Models\RegularPerformancePeriod;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegularPerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $performancePeriods = RegularPerformancePeriod::select('period_id', 'start_date')->get();

            $performanceCategories = PerformanceCategory::select('perf_category_id')->get();

            foreach ($performancePeriods as $period) {
                $employeeIds = Employee::whereHas('status', function ($query) {
                    $query->where('employment_statuses.emp_status_id', EmploymentStatus::REGULAR);
                })
                    ->whereHas('application', function ($query) use ($period) {
                        $query->where('hired_at', '<=', $period->start_date);
                    })
                    ->pluck('employee_id');


                foreach ($employeeIds as $employeeId) {

                    $evaluator = Employee::inRandomOrder()->select('employee_id')->first();

                    $startDate = new DateTime($period->start_date);
                    $evaluatorSignedAt = $startDate->modify('+' . rand(1, 7) . ' days');

                    $secondarySignedAt = (clone $evaluatorSignedAt)->modify('+2 days');

                    $thirdSignedAt = (clone $secondarySignedAt)->modify('+2 days');

                    $fourthSignedAt = (clone $thirdSignedAt)->modify('+2 days');

                    $secondaryApprover = Employee::whereHas('areaManagerOf')->select('employee_id')->first();

                    if (!$secondaryApprover) {
                        $secondaryApprover = Employee::inRandomOrder()->select('employee_id')->first();
                    }

                    $thirdApprover = Employee::whereHas('headOf')->select('employee_id')->first();

                    if (!$thirdApprover) {
                        $thirdApprover = Employee::inRandomOrder()->select('employee_id')->first();
                    }

                    $fourthApprover = Employee::whereHas('supervisorOf')->select('employee_id')->first();

                    if (!$fourthApprover) {
                        $fourthApprover = Employee::inRandomOrder()->select('employee_id')->first();
                    }

                    // dd([
                    //     'evaluator' => $evaluator,
                    //     'secondaryApprover' => $secondaryApprover,
                    //     'thirdApprover' => $thirdApprover,
                    //     'fourthApprover' => $fourthApprover,
                    // ]);

                    $evaluation = RegularPerformance::create([
                        'period_id' => $period->period_id,
                        'evaluatee' => $employeeId,
                        'evaluator' => $evaluator->employee_id,
                        'evaluator_signed_at' => $evaluatorSignedAt,
                        'secondary_approver' => $secondaryApprover ? $secondaryApprover->employee_id : null,
                        'secondary_approver_signed_at' => $secondaryApprover ? $secondarySignedAt : null,
                        'third_approver' => $thirdApprover ? $thirdApprover->employee_id : null,
                        'third_approver_signed_at' => $thirdApprover ? $thirdSignedAt : null,
                        'fourth_approver' => $fourthApprover ? $fourthApprover->employee_id : null,
                        'fourth_approver_signed_at' => $fourthApprover ? $fourthSignedAt : null,
                        'is_employee_acknowledged' => rand(0, 3) > 0 ? 1 : 0,
                    ]);

                    $isFailed = rand(0, 1);

                    $baseRating = 3;
                    $baseRating += $isFailed ? 1 : -2;

                    foreach ($performanceCategories as $category) {
                        $evaluation->categoryRatings()->create([
                            'perf_category_id' => $category->perf_category_id,
                            'perf_rating_id' => $baseRating,
                        ]);
                    }
                }
            }
        });
    }
}
