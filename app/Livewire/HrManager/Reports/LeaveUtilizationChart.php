<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use Illuminate\View\View;
use App\Services\ReportService;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Cache;

class LeaveUtilizationChart extends Component
{
    public $year;

    #[Locked]
    public $leaveData;

    public function mount(ReportService $reportService): void
    {
        $key = sprintf(config('cache.keys.reports.leave_utilization_rate'), $this->year);

        $this->leaveData = Cache::get($key);

        if ($this->leaveData) return;

        $leaveUtilizationRate = $reportService->getLeaveUtilizationRate($this->year);

        $this->leaveData = (object) [
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
        ];

        Cache::forever($key, $this->leaveData);
    }

    public function render(): View
    {
        return view('livewire.hr-manager.reports.leave-utilization-chart');
    }
}
