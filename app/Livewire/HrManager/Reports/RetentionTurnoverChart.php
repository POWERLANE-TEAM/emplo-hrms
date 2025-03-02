<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use App\Services\ReportService;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Cache;

class RetentionTurnoverChart extends Component
{
    public $year;

    #[Locked]    
    public $retentionData;

    public function mount(ReportService $reportService)
    {
        $key = sprintf(config('cache.keys.reports.retention_turnover_rate'), $this->year);

        $this->retentionData = Cache::get($key);

        if ($this->retentionData) return;

        $this->retentionData = $reportService->getRetentionAndTurnoverRate($this->year);

        Cache::forever($key, $this->retentionData);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.retention-turnover-chart');
    }
}