<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class LeaveUtilizationChart extends Component
{
    public $leaveData;

    public function mount()
    {
        $this->leaveData = [
            'all' => ['used' => 10342, 'total' => 10666],
            'sick' => ['used' => 900, 'total' => 1500],
            'vacation' => ['used' => 1350, 'total' => 1500],
            'paternity' => ['used' => 1050, 'total' => 1500],
        ];
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.leave-utilization-chart', [
            'leaveData' => $this->leaveData,
        ]);
    }
}
