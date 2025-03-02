<?php

namespace App\Livewire\HrManager\Reports;

use App\Services\ReportService;
use App\Traits\AttendanceUtils;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AverageAttendanceChart extends Component
{
    use AttendanceUtils;

    public $year;

    #[Locked]
    public $attendanceData;

    #[Locked]
    public $holidays;

    public function mount(ReportService $reportService)
    {
        $key = sprintf(config('cache.keys.reports.attendance_rate'), $this->year);

        $this->attendanceData = Cache::get($key);

        if ($this->attendanceData) {
            return;
        }

        $attendanceRates = $reportService->getAttendanceRates($this->year, $this->holidays);

        $this->attendanceData = (object) [
            'yearly' => $attendanceRates->yearlyData,
            'monthly' => $attendanceRates->monthlyData,
        ];

        Cache::forever($key, $this->attendanceData);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.average-attendance-chart');
    }
}
