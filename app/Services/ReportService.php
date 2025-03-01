<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Employee;
use App\Models\Incident;
use App\Models\Training;
use App\Models\Applicant;
use App\Enums\IssueStatus;
use App\Enums\TrainingStatus;
use App\Models\RegularPerformance;
use Illuminate\Support\Facades\DB;
use App\Models\RegularPerformancePeriod;
use App\Models\ProbationaryPerformancePeriod;

class ReportService
{
    public function __construct()
    {
        //
    }

    public function getAllEmployeesTotalTenure(int $year): float|int
    {
        $employees = Employee::query()
            ->with([
                'lifecycle' => fn ($q) => $q->select([
                    'employee_id',
                    'started_at',
                ])
            ])
            ->whereHas('lifecycle', function ($query) use ($year) {
                $query->whereYear('started_at', $year);
            })
            ->get();

        $totalTenure = $employees->sum(function ($employee)  {
            return abs(now()->diffInDays($employee->lifecycle->started_at)) / 365.25;
        });

        return $totalTenure > 0 
            ? round($totalTenure / count($employees), 1) 
            : 0;
    }

    public function getAllNewHiresAndApplicants(int $year): object
    {
        $applicants = Applicant::whereYear('created_at', $year)
            ->with([
                'application' => fn ($q) => $q->select([
                    'applicant_id',
                    'is_passed',
                ])
            ])
            ->get();

        $hires = $applicants->filter(fn ($applicant) => $applicant->application->is_passed);

        return (object) [
            'hires' => $hires->count(),
            'applicants' => $applicants->count(),
        ];
    }

    public function getEvaluationSuccessRate(int $year): float|int
    {
        $probationary = $this->getProbationaryEvaluationMetrics($year);
        $regular = $this->getRegularEvaluationMetrics($year);

        $passed = $probationary->passed + $regular->passed;
        $total = $probationary->total + $probationary->total;

        return $passed > 0 && $total > 0
            ? round(($passed / $total) * 100, 1)
            : 0;
    }

    public function getRegularEvaluationMetrics(int $year): object
    {
        $performancePeriod = RegularPerformancePeriod::query()
            ->whereYear('start_date', $year)
            ->whereYear('end_date', $year)
            ->first();

        $regularPerformances = RegularPerformance::query()
            ->where('period_id', $performancePeriod->period_id)
            ->with('categoryRatings.rating')
            ->get();

        $total = $regularPerformances->count();
        $passed = 0;

        foreach ($regularPerformances as $performance) {
            $finalRating = $performance->getFinalRatingAttribute();

            if ($finalRating['ratingAvg'] >= 2) {
                $passed++;
            }
        }

        return (object) compact('total', 'passed');
    }

    public function getProbationaryEvaluationMetrics(int $year): object
    {
        $evaluationData = ProbationaryPerformancePeriod::query()
            ->whereYear('start_date', $year)
            ->whereYear('end_date', $year)
            ->with([
                'details.categoryRatings' => function ($query) {
                    $query->select([
                        'probationary_performance_id',
                        DB::raw('AVG(perf_rating_id) as average_rating'),
                    ])->groupBy('probationary_performance_id');
                },
                'probationaryEvaluatee'
            ])
            ->get()
            ->map(function ($period) {
                $averageRatings = $period->details
                    ->pluck('categoryRatings.*.average_rating')
                    ->flatten()->avg();
        
                return [
                    'evaluatee' => $period->probationaryEvaluatee,
                    'period_name' => $period->period_name,
                    'start_date' => $period->start_date,
                    'average_rating' => round($averageRatings, 2),
                ];
            });

        $total = $evaluationData->count();

        $passed = $evaluationData->filter(function ($data) {
            return $data['average_rating'] >= 2;
        })->count();

        return (object) compact('total', 'passed');
    }

    public function getCompletedAndTotalIncidents(int $year): object
    {
        $incidents = Incident::query()
            ->select([
                'created_at',
                'status',
            ])
            ->whereYear('created_at', $year)
            ->get();
        
        $total = $incidents->count();

        $completed = $incidents->filter(function ($incident) {
            return in_array($incident->status, [
                IssueStatus::CLOSED->value,
                IssueStatus::RESOLVED->value,
            ]);
        })->count();

        $percentage = $this->getPercentage($completed, $total);

        return (object) compact('completed', 'total', 'percentage');
    }

    public function getCompletedAndTotalIssues(int $year): object
    {
        $issues = Issue::query()
            ->select([
                'filed_at',
                'status'
            ])
            ->whereYear('filed_at', $year)
            ->get();

        $total = $issues->count();

        $completed = $issues->filter(function ($issue) {
            return in_array($issue->status, [
                IssueStatus::CLOSED->value,
                IssueStatus::RESOLVED->value,
            ]);
        })->count();

        $percentage = $this->getPercentage($completed, $total);

        return (object) compact('completed', 'total', 'percentage');
    }

    public function getCompletedAndTotalTrainings(int $year): object
    {
        $trainings = Training::query()
            ->select([
                'created_at',
                'completion_status',
            ])
            ->whereYear('created_at', $year)
            ->get();

        $total = $trainings->count();

        $completed = $trainings->filter(function ($training) {
            return $training->completion_status === TrainingStatus::COMPLETED->value;
        })->count();

        $percentage = $this->getPercentage($completed, $total);

        return (object) compact('completed', 'total', 'percentage');
    }

    public function getPercentage($completed, $total)
    {
        return $total > 0
            ? round(($completed / $total) * 100)
            : 0;
    }
}