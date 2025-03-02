<?php

namespace App\Livewire\HrManager\Reports;

use App\Services\ReportService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class KeyMetrics extends Component
{
    #[Reactive]
    public $year;

    #[Locked]
    public $metrics;

    private $reportService;

    public function mount(ReportService $reportService)
    {
        $key = sprintf(config('cache.keys.reports.key_metrics'), $this->year);

        $this->metrics = Cache::get($key);

        if ($this->metrics) {
            return;
        }

        $this->reportService = $reportService;

        $this->metrics = (object) [
            'incidents' => $this->reportService->getCompletedAndTotalIncidents($this->year),
            'issues' => $this->reportService->getCompletedAndTotalIssues($this->year),
            'trainings' => $this->reportService->getCompletedAndTotalTrainings($this->year),
        ];

        Cache::forever($key, $this->metrics);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.key-metrics');
    }
}
