<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use App\Services\ReportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheAnnualReport extends Command
{
    private $year;

    private $holidays;

    public function __construct(private ReportService $reportService)
    {
        parent::__construct();

        $this->year = now()->subYear()->year;

        $this->holidays = Holiday::all();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache the annual report data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employeeMetricsCachedKey           = sprintf(config("cache.keys.reports.employee_metrics"), $this->year);
        $keyMetricsCachedKey                = sprintf(config("cache.keys.reports.key_metrics"), $this->year);
        $attendanceRateCachedKey            = sprintf(config("cache.keys.reports.attendance_rate"), $this->year);
        $absenteeismAvgsCachedKey           = sprintf(config("cache.keys.reports.absenteeism"), $this->year);
        $retentionTurnoverRateCachedKey     = sprintf(config("cache.keys.reports.retention_turnover_rate"), $this->year);
        $issueResolutionTimeRateCachedKey   = sprintf(config("cache.keys.reports.issue_resolution_time_rate"), $this->year);
        $leaveUtilizationRateCachedKey      = sprintf(config("cache.keys.reports.leave_utilization_rate"), $this->year);

        // employee metrics
        Cache::forever($employeeMetricsCachedKey, (object) [
            'employee_tenure'       => $this->reportService->getAllEmployeesTotalTenure($this->year),
            'new_hires'             => $this->reportService->getAllNewHiresAndApplicants($this->year),
            'evaluation_success'    => $this->reportService->getEvaluationSuccessRate($this->year),
        ]);

        // key metrics
        Cache::forever($keyMetricsCachedKey, (object) [
            'incidents' => $this->reportService->getCompletedAndTotalIncidents($this->year),
            'issues'    => $this->reportService->getCompletedAndTotalIssues($this->year),
            'trainings' => $this->reportService->getCompletedAndTotalTrainings($this->year),
        ]);

        // attendance rate
        $attendanceRates = $this->reportService->getAttendanceRates($this->year, $this->holidays);
        Cache::forever($attendanceRateCachedKey, (object) [
            'yearly' => $attendanceRates->yearlyData,
            'monthly' => $attendanceRates->monthlyData,
        ]);

        // absenteeism average
        $absenteeismAvgs = $this->reportService->getAbsenteeismAverage($this->year, $this->holidays);
        Cache::forever($absenteeismAvgsCachedKey, (object) [
            'yearly' => $absenteeismAvgs->yearlyData,
            'monthly' => $absenteeismAvgs->monthlyData,
        ]);

        // retention and turnover rate
        Cache::forever($retentionTurnoverRateCachedKey, $this->reportService->getRetentionAndTurnoverRate($this->year));

        // issue resolution time rate
        $issueResolutionTimeRate = $this->reportService->getIssueResolutionTimeRate($this->year);
        Cache::forever($issueResolutionTimeRateCachedKey, [
            'yearly' => $issueResolutionTimeRate->yearlyData,
            'monthly' => $issueResolutionTimeRate->monthlyData,
        ]);

        // leave utilization rate
        $leaveUtilizationRate = $this->reportService->getLeaveUtilizationRate($this->year);
        Cache::forever($leaveUtilizationRateCachedKey, (object) [
            'all' => (object) [
                'used' => $leaveUtilizationRate->totalUsedSilCredits, 
                'total' => $leaveUtilizationRate->totalSilCredits
            ],
            'sick' => (object) [
                'used' => $leaveUtilizationRate->usedSickCredits, 
                'total' => $leaveUtilizationRate->credits
            ],
            'vacation' => (object) [
                'used' => $leaveUtilizationRate->usedVacationCredits, 
                'total' => $leaveUtilizationRate->credits
            ],
        ]);
    }
}
