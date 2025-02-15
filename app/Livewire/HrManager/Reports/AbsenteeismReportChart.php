<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Enums\BiometricPunchType;
use Illuminate\Support\Facades\Cache;

class AbsenteeismReportChart extends Component
{
    // #[Reactive]
    public $year;

    #[Locked]
    public $absenteeismData;

    #[Locked]
    public $yearlyData = [];

    #[Locked]
    public $monthlyData = [];

    #[Locked]
    public $holidays;

    public function mount()
    {
        $key = sprintf(config('cache.keys.reports.absenteeism'), $this->year);

        $this->absenteeismData  = Cache::get($key);

        if ($this->absenteeismData) {
            $this->monthlyData = $this->absenteeismData['monthly'];
            $this->yearlyData = $this->absenteeismData['yearly'];          
            return;  
        }

        $attendanceLogs = AttendanceLog::whereYear('timestamp', $this->year)
            ->get()
            ->where('type', BiometricPunchType::CHECK_IN->value)
            ->where(fn ($log) => $log->timestamp->copy()->format('m-d'))
            ->whereNotIn('timestamp', $this->holidays->toArray())
            ->groupBy(function($date) {
                return $date->timestamp->copy()->format('Y-m');
            });    
    
        $yearlyTotalAbsences = 0;
        $yearlyTotalScheduled = 0;
    
        $monthlyAbsences = [];

        $totalEmployees = Employee::activeEmploymentStatus()->get()->count();
    
        $sortedMonths = $attendanceLogs->keys()->sort()->values();

        foreach ($sortedMonths as $month) {

            $logs = $attendanceLogs[$month];
            
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
            'monthly_average' => round($yearlyTotalAbsences / 12, 2),
        ];
    
        $this->absenteeismData = [
            'yearly' => $this->yearlyData,
            'monthly' => $this->monthlyData,
        ];

        Cache::forever($key, $this->absenteeismData);
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

