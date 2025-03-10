<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use App\Services\ReportService;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Cache;

class IssueResolutionChart extends Component
{
    public $year;

    #[Locked]
    public $issueResolutionData = [];

    public function mount(ReportService $reportService)
    {
        $key = sprintf(config('cache.keys.reports.issue_resolution_time_rate'), $this->year);

        $this->issueResolutionData  = Cache::get($key);

        if ($this->issueResolutionData) return;

        $issueResolutionTimeRate = $reportService->getIssueResolutionTimeRate($this->year);

        $this->issueResolutionData = [
            'yearly' => $issueResolutionTimeRate->yearlyData,
            'monthly' => $issueResolutionTimeRate->monthlyData,
        ];

        Cache::forever($key, $this->issueResolutionData);
    }

    public function render()
    {   
        return view('livewire.hr-manager.reports.issue-resolution-chart');
    }
}
