<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use App\Models\EmployeeLifecycle;

class RetentionTurnoverChart extends Component
{
    public $year;
    public $retentionData;

    public function mount()
    {
        $this->fetchRetentionData();
    }

    /**
     * Fetch retention data for the selected year.
     */
    public function fetchRetentionData()
    {
        $totalEmployeesStart = EmployeeLifecycle::whereYear('started_at', $this->year)
            ->count();

        $employeesLeft = EmployeeLifecycle::whereYear('separated_at', $this->year)
            ->whereNotNull('separated_at')
            ->count();

        $totalEmployeesEnd = $totalEmployeesStart - $employeesLeft;

        $data = [
            'total_employees_start' => $totalEmployeesStart,
            'employees_left' => $employeesLeft,
            'total_employees_end' => $totalEmployeesEnd,
        ];

        $turnoverRate = ($totalEmployeesStart > 0)
            ? ($employeesLeft / $totalEmployeesStart) * 100
            : 0;
        $retentionRate = 100 - $turnoverRate;

        $this->retentionData = [
            'year' => $this->year,
            'total_start' => $data['total_employees_start'],
            'total_left' => $data['employees_left'],
            'total_stayed' => $data['total_employees_end'],
            'turnover_rate' => round($turnoverRate, 1),
            'retention_rate' => round($retentionRate, 1)
        ];
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.retention-turnover-chart', [
            'retentionData' => $this->retentionData
        ]);
    }
}