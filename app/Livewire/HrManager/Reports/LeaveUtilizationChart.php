<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class LeaveUtilizationChart extends Component
{

    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 1. Total count of ALL ENTITLED leave days across ALL leave types.
     * 2. Total count of ALL REMAINING leave days across ALL leave types.
     * 
     * 3. Total count of ENTITLED leave days for EACH LEAVE TYPE.
     * 4. Total count of entitled leave days for EACH LEAVE TYPE.
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * ► After fetching, replace the leaveData array in mount() function.
     * 
     */


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
