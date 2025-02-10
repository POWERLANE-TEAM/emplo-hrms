<?php

namespace App\Livewire\HrManager\Reports;

use App\Models\Holiday;
use Livewire\Component;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Enums\BiometricPunchType;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\DB;

class AverageAttendanceChart extends Component
{
    // #[Reactive]
    public $year;

    public $attendanceData;

    public $yearlyData = [];

    public $monthlyData = [];

    #[Locked]
    public $holidays;

    public function mount()
    {
        // $key = sprintf(config('cache.keys.reports.attendance_rate'), $this->year);
        // implement caching

        $attendanceLogs = AttendanceLog::whereYear('timestamp', $this->year)
            ->get()
            ->where('type', BiometricPunchType::CHECK_IN->value)
            ->where(fn ($log) => $log->timestamp->format('m-d'))
            ->whereNotIn('timestamp', $this->holidays->toArray())
            ->groupBy(function ($date) {
                return $date->timestamp->format('Y-m');
            });        
    
        $yearlyTotalAttended = 0;
        $yearlyTotalScheduled = 0;
    
        $totalEmployees = Employee::activeEmploymentStatus()->get()->count();

        $sortedMonths = $attendanceLogs->keys()->sort()->values();

        foreach ($sortedMonths as $month) {

            $logs = $attendanceLogs[$month];
    
            $totalWorkdays = $this->getWorkdaysInMonth($month);

            $daysAttended = $logs->count();
            $totalScheduledDays = $totalEmployees * $totalWorkdays;
    
            $totalScheduledDays = $totalEmployees * $totalWorkdays;
            $attendanceRate = ($daysAttended / $totalScheduledDays) * 100;
    
            $yearlyTotalAttended += $daysAttended;
            $yearlyTotalScheduled += $totalScheduledDays;

            $this->monthlyData[$month] = [
                'attendance_rate' => round($attendanceRate, 2),
                'total_employees' => $totalEmployees,
                'workdays' => $totalWorkdays,
                'days_attended' => $daysAttended,
                'total_scheduled' => $totalScheduledDays,
            ];
        }
    
        $year = substr($this->year, 0, 4);
        $attendanceRate = $yearlyTotalAttended > 0
            ? round(($yearlyTotalAttended / $yearlyTotalScheduled) * 100, 2)
            : 0;

        $this->yearlyData[$year] = [
            'attendance_rate' => $attendanceRate,
            'total_days_attended' => $yearlyTotalAttended,
            'total_scheduled_days' => $yearlyTotalScheduled
        ];
    
        $this->attendanceData = [
            'yearly' => $this->yearlyData,
            'monthly' => $this->monthlyData,
        ];
    }

    public function getWorkdaysInMonth($month)
    {
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        $workdays = 0;
        $currentDate = $startDate;

        $holidays = $this->holidays->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')->toArray();

        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday() && ! in_array($currentDate->toDateString(), $holidays)) {
                $workdays++;
            }
            $currentDate->addDay();
        }
    
        return $workdays;
    }

    public function render()
    {   
        return view('livewire.hr-manager.reports.average-attendance-chart', [
            'attendanceData' => $this->attendanceData,
            'yearlyData' => $this->yearlyData,
            'monthlyData' => $this->monthlyData
        ]);
    }
}