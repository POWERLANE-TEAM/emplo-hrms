<?php

namespace App\Livewire\HrManager\Reports;

use App\Services\ReportService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class EmployeeMetrics extends Component
{
    #[Reactive]
    public $year;

    #[Locked]
    public $metrics = [];

    private $reportService;

    public function mount(ReportService $reportService)
    {
        $key = sprintf(config('cache.keys.reports.employee_metrics'), $this->year);

        $this->metrics = Cache::get($key);

        if ($this->metrics) {
            return;
        }

        $this->reportService = $reportService;

        $this->metrics = (object) [
            'employee_tenure' => $this->reportService->getAllEmployeesTotalTenure($this->year),
            'new_hires' => $this->reportService->getAllNewHiresAndApplicants($this->year),
            'evaluation_success' => $this->reportService->getEvaluationSuccessRate($this->year),
        ];

        Cache::forever($key, $this->metrics);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.employee-metrics');
    }
}
