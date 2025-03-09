<?php

namespace App\Services;

use App\Enums\IssueStatus;
use App\Enums\TrainingStatus;
use App\Models\Applicant;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeLifecycle;
use App\Models\Incident;
use App\Models\Issue;
use App\Models\ProbationaryPerformancePeriod;
use App\Models\RegularPerformance;
use App\Models\RegularPerformancePeriod;
use App\Models\Training;
use App\Traits\AttendanceUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ReportService
{
    use AttendanceUtils;

    public function getAllEmployeesTotalTenure(int $year): float|int
    {
        $employees = Employee::query()
            ->with([
                'lifecycle' => fn ($q) => $q->select([
                    'employee_id',
                    'started_at',
                ]),
            ])
            ->whereHas('lifecycle', function ($query) use ($year) {
                $query->whereYear('started_at', $year);
            })
            ->get();

        $totalTenure = $employees->sum(function ($employee) {
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
                ]),
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
                'probationaryEvaluatee',
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
                'status',
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

    public function getPercentage(int|float $completed, int|float $total): float|int
    {
        return $total > 0
            ? round(($completed / $total) * 100)
            : 0;
    }

    public function getAttendanceRates(int $year, Collection|array $holidays): object
    {
        $attendanceService = App::make(AttendanceService::class);

        $attendanceLogs = $attendanceService->getAnnualDtrLogs($year, $holidays);

        $yearlyTotalAttended = 0;
        $yearlyTotalScheduled = 0;

        $totalEmployees = Employee::activeEmploymentStatus()->count();

        $sortedDates = $attendanceLogs->keys()->sort()->values();

        $monthlyData = [];
        $yearlyData = [];

        foreach ($sortedDates as $date) {
            $logs = $attendanceLogs[$date];

            $totalWorkdays = $this->getTotalWorkdaysInMonth($date, $holidays);

            $daysAttended = $logs->count();
            $totalScheduledDays = $totalEmployees * $totalWorkdays;

            $totalScheduledDays = $totalEmployees * $totalWorkdays;
            $attendanceRate = ($daysAttended / $totalScheduledDays) * 100;

            $yearlyTotalAttended += $daysAttended;
            $yearlyTotalScheduled += $totalScheduledDays;

            $monthlyData[$date] = (object) [
                'attendance_rate' => round($attendanceRate, 2),
                'total_employees' => $totalEmployees,
                'work_days' => $totalWorkdays,
                'days_attended' => $daysAttended,
                'total_scheduled' => $totalScheduledDays,
            ];
        }

        $year = substr($year, 0, 4);
        $attendanceRate = $yearlyTotalAttended > 0
            ? round(($yearlyTotalAttended / $yearlyTotalScheduled) * 100, 2)
            : 0;

        $yearlyData[$year] = (object) [
            'attendance_rate' => $attendanceRate,
            'total_days_attended' => $yearlyTotalAttended,
            'total_scheduled_days' => $yearlyTotalScheduled,
        ];

        return (object) compact('monthlyData', 'yearlyData');
    }

    public function getAbsenteeismAverage(int $year, Collection|array $holidays): object
    {
        $attendanceService = App::make(AttendanceService::class);

        $attendanceLogs = $attendanceService->getAnnualDtrLogs($year, $holidays);

        $yearlyTotalAbsences = 0;
        $yearlyTotalScheduled = 0;
        $monthlyAbsences = [];

        $totalEmployees = Employee::activeEmploymentStatus()->count();

        $sortedDates = $attendanceLogs->keys()->sort()->values();

        foreach ($sortedDates as $date) {
            $logs = $attendanceLogs[$date];

            $totalWorkdays = $this->getTotalWorkdaysInMonth($date, $holidays);

            $daysAttended = $logs->count();

            $totalScheduledDays = $totalEmployees * $totalWorkdays;

            $daysAbsent = $totalScheduledDays - $daysAttended;

            $yearlyTotalAbsences += $daysAbsent;
            $yearlyTotalScheduled += $totalScheduledDays;

            $monthlyAbsences[$date] = $daysAbsent;

            $monthlyData[$date] = (object) [
                'absences' => $daysAbsent,
            ];
        }

        $year = substr($year, 0, 4);

        $yearlyData[$year] = (object) [
            'total_absences' => $yearlyTotalAbsences,
            'monthly_average' => round($yearlyTotalAbsences / 12, 2),
        ];

        return (object) compact('monthlyData', 'yearlyData');
    }

    public function getRetentionAndTurnoverRate(int $year): object
    {
        $totalStartingEmployees = EmployeeLifecycle::query()
            ->whereYear('started_at', $year)
            ->count();

        $totalSeparatedEmployees = EmployeeLifecycle::query()
            ->whereYear('separated_at', $year)
            ->whereNotNull('separated_at')
            ->count();

        $totalRemainingEmployees = $totalStartingEmployees - $totalSeparatedEmployees;

        $turnoverRate = $totalStartingEmployees > 0
            ? ($totalSeparatedEmployees / $totalStartingEmployees) * 100
            : 0;

        $retentionRate = 100 - $turnoverRate;

        return (object) [
            'total_starting' => $totalStartingEmployees,
            'total_separated' => $totalSeparatedEmployees,
            'total_remaining' => $totalRemainingEmployees,
            'turnover_rate' => round($turnoverRate, 1),
            'retention_rate' => round($retentionRate, 1),
        ];
    }

    public function getIssueResolutionTimeRate(int $year): object
    {
        $issues = Issue::query()
            ->whereYear('filed_at', $year)
            ->ofStatus([
                IssueStatus::RESOLVED->value,
                IssueStatus::CLOSED->value,
            ])
            ->get()
            ->map(function ($issue) {
                return [
                    'date_submitted' => $issue->filed_at,
                    'date_resolved' => $issue->status_marked_at,
                ];
            })
            ->toArray();

        $yearlyData = [];
        $monthlyData = [];

        foreach ($issues as $issue) {
            $dateSubmitted = strtotime($issue['date_submitted']);
            $dateResolved = strtotime($issue['date_resolved']);

            $resolvedDays = ($dateResolved - $dateSubmitted) / 86400;

            $issue['resolved_days'] = $resolvedDays;

            $issue['resolved_date'] = date('Y-m-d', $dateResolved);

            $year = date('Y', $dateResolved);
            $month = date('Y-m', $dateResolved);

            if (! isset($yearlyData[$year])) {
                $yearlyData[$year] = ['total_days' => 0, 'count' => 0];
            }
            $yearlyData[$year]['total_days'] += $resolvedDays;
            $yearlyData[$year]['count']++;

            if (! isset($monthlyData[$month])) {
                $monthlyData[$month] = ['total_days' => 0, 'count' => 0];
            }
            $monthlyData[$month]['total_days'] += $resolvedDays;
            $monthlyData[$month]['count']++;
        }

        foreach ($yearlyData as $year => &$data) {
            $data['average_days'] = $data['count'] > 1
                ? $data['total_days'] / $data['count']
                : 0;
        }

        foreach ($monthlyData as $month => &$data) {
            $data['average_days'] = $data['count'] > 1
                ? $data['total_days'] / $data['count']
                : 0;
        }

        return (object) compact('yearlyData', 'monthlyData');
    }

    public function getLeaveUtilizationRate(int $year)
    {
        $silCreditService = App::make(ServiceIncentiveLeaveCreditService::class);

        $activeEmployees = $silCreditService->getActiveEmployees();

        $credits = $activeEmployees->sum(function ($employee) use ($silCreditService) {
            $serviceDuration = $silCreditService->getServiceDuration($employee->jobDetail->hired_at);

            return $silCreditService->resetSilCredits($serviceDuration);
        });

        $totalSilCredits = $credits * 2;

        $usedVacationCredits = EmployeeLeave::query()
            ->whereHas('category', function ($query) {
                $query->where('leave_category_name', 'Vacation Leave');
            })
            ->whereYear('filed_at', $year)
            ->whereNotNull('fourth_approver_signed_at')
            ->count();

        $usedSickCredits = EmployeeLeave::query()
            ->whereHas('category', function ($query) {
                $query->where('leave_category_name', 'Sick Leave');
            })
            ->whereYear('filed_at', $year)
            ->whereNotNull('fourth_approver_signed_at')
            ->count();

        $totalUsedSilCredits = $usedVacationCredits + $usedSickCredits;

        return (object) compact(
            'credits',
            'totalSilCredits',
            'totalUsedSilCredits',
            'usedVacationCredits',
            'usedSickCredits'
        );
    }
}
