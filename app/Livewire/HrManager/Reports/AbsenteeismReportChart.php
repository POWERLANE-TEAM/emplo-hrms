<?php

namespace App\Livewire\HrManager\Reports;

use App\Models\Holiday;
use Livewire\Component;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Livewire\Attributes\Locked;
use App\Enums\BiometricPunchType;

class AbsenteeismReportChart extends Component
{
    // #[Reactive]
    public $year;

    public $absenteeismData;

    public $yearlyData = [];

    public $monthlyData = [];

    #[Locked]
    public $holidays;

    public function mount()
    {
        $attendanceLogs = AttendanceLog::whereYear('timestamp', $this->year)
            ->where('type', BiometricPunchType::CHECK_IN)
            ->whereHas('employee', function ($query) {
                $query->whereNotIn('timestamp', function ($subQuery) {
                    $subQuery->select('date')
                        ->from('holidays')
                        ->whereRaw('EXTRACT(MONTH FROM date) = ?', [Carbon::parse($this->year)->month]);
                });
            })
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->timestamp)->format('Y-m');
            });    
    
        $this->yearlyData = [];
        $this->monthlyData = [];
    
        $yearlyTotalAbsences = 0;
        $yearlyTotalScheduled = 0;
    
        $monthlyAbsences = [];

        $totalEmployees = Employee::whereHas('status', 
            fn ($query) => $query->whereIn('emp_status_name', [
                EmploymentStatus::REGULAR->label(),
                EmploymentStatus::PROBATIONARY->label(),
            ]))
            ->get()
            ->count();
    
        foreach ($attendanceLogs as $month => $logs) {
            $totalWorkdays = $this->getWorkdaysInMonth($month);

            $daysAttended = $logs->count();

            $totalScheduledDays = $totalEmployees * $totalWorkdays;

            $daysAbsent = $totalScheduledDays - $daysAttended;
    
            $yearlyTotalAbsences += $daysAbsent;
            $yearlyTotalScheduled += $totalScheduledDays;

            $monthlyAbsences[$month] = $daysAbsent;

            $this->monthlyData[$month] = [
                'absences' => $daysAbsent,
            ];
        }

        $year = substr($this->year, 0, 4);
    
        $this->yearlyData[$year] = [
            'total_absences' => $yearlyTotalAbsences,
            'monthly_average' => round($yearlyTotalAbsences / 12, 2), // Calculate monthly average absences
        ];
    
        $this->absenteeismData = [
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
        return view('livewire.hr-manager.reports.absenteeism-report-chart', [
            'absenteeismData' => $this->absenteeismData,
            'yearlyData' => $this->yearlyData,
            'monthlyData' => $this->monthlyData
        ]);
    }
}

