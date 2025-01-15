<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Applicant;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Reactive;
use App\Models\RegularPerformance;
use Illuminate\Support\Facades\DB;
use App\Models\RegularPerformancePeriod;
use App\Models\ProbationaryPerformancePeriod;

class EmployeeMetrics extends Component
{
    #[Reactive]
    public $year;

    public $metrics = [];

    public function mount()
    {
        $this->year;

        $this->metrics = [
            'employee_tenure' => $this->calculateEmployeeTenure(),
            'new_hires' => $this->calculateNewHires(),
            'evaluation_success' => $this->calculateEvaluationSuccess(),
        ];
    }

    private function calculateEmployeeTenure()
    {
        $employees = Employee::query()
            ->with('lifecycle')
            ->whereHas('lifecycle', function ($query) {
                $query->whereYear('started_at', $this->year);
            })
            ->get()
            ->map(function ($employee) {
                return (object) [
                    'date_start' => $employee->lifecycle->started_at
                ];
            });

        $totalTenure = 0;
    
        foreach ($employees as $employee) {
            $startDate = Carbon::parse($employee->date_start);
            $tenure = abs(now()->diffInDays($startDate)) / 365.25;
    
            if ($tenure > 0) {
                $totalTenure += $tenure;
            }
        }
    
        return $totalTenure > 0 
            ? round($totalTenure / count($employees), 1) 
            : 0;
    }

    private function calculateNewHires()
    {
        $applicants = Applicant::whereYear('created_at', $this->year)
            ->with('application')
            ->get();

        $hires = $applicants->filter(function ($applicant) {
            return $applicant->application->is_passed;
        });

        return [
            'hires' => $hires->count(),
            'applicants' => $applicants->count(),
        ];
    }

    private function calculateEvaluationSuccess()
    {
        $probationary = $this->getProbationaryEvaluationMetrics();
        $regular = $this->getRegularEvaluationMetrics();

        $passed = $probationary->passed + $regular->passed;
        $total = $probationary->total + $probationary->total;

        return round(($passed / $total) * 100, 1);
    }

    private function getRegularEvaluationMetrics()
    {
        $performancePeriod = RegularPerformancePeriod::query()
            ->whereYear('start_date', $this->year)
            ->whereYear('end_date', $this->year)
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

    private function getProbationaryEvaluationMetrics()
    {
        $evaluationData = ProbationaryPerformancePeriod::query()
            ->whereYear('start_date', $this->year)
            ->whereYear('end_date', $this->year)
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

    public function render()
    {
        return view('livewire.hr-manager.reports.employee-metrics', [
            'metrics' => $this->metrics,
        ]);
    }
}
