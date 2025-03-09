<?php

namespace App\Livewire\HrManager\Reports;

use App\Services\ReportService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AbsenteeismReportChart extends Component
{
    public $year;

    #[Locked]
    public $absenteeismData;

    #[Locked]
    public $holidays;

    public function mount(ReportService $reportService)
    {
        $key = sprintf(config('cache.keys.reports.absenteeism'), $this->year);

        $this->absenteeismData = Cache::get($key);

        if ($this->absenteeismData) {
            return;
        }

        $absenteeismAvgs = $reportService->getAbsenteeismAverage($this->year, $this->holidays);

        $this->absenteeismData = (object) [
            'yearly' => $absenteeismAvgs->yearlyData,
            'monthly' => $absenteeismAvgs->monthlyData,
        ];

        Cache::forever($key, $this->absenteeismData);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.absenteeism-report-chart');
    }
}
