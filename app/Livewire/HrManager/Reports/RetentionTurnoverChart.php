<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class RetentionTurnoverChart extends Component
{

    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 1. 'total_employees_start': Start of year total count of employees.
     * 2. 'total_employees_end': End of year total employees count.
     * --> These needs to be store somewhere in the db.
     * 
     * 3. 'employees_left': Employees who left during the year. This is determined by their resigned_date.
     * 
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * 
     */

    public $selectedYear;

    public $retentionData;

    public function mount()
    {

        $this->selectedYear;
        logger('RETENTION CHART - Selected Year initialized to: ' . $this->selectedYear);

        // Sample yearly data
        $data = [
            'total_employees_start' => 150,    // Start of year
            'employees_left' => 3,            // Left during the year
            'total_employees_end' => 147        // End of year
        ];

        // Calculate rates
        $turnover_rate = ($data['employees_left'] / $data['total_employees_start']) * 100;
        $retention_rate = 100 - $turnover_rate;

        $this->retentionData = [
            'year' => date('Y'),
            'total_start' => $data['total_employees_start'],
            'total_left' => $data['employees_left'],
            'total_stayed' => $data['total_employees_end'],
            'turnover_rate' => round($turnover_rate, 1),
            'retention_rate' => round($retention_rate, 1)
        ];
    }

    public function updated($name)
    {
        if ($name === 'selectedYear' && !empty($this->selectedYear)) {
            logger('RETENTION CHART - Selected Year updated to: ' . $this->selectedYear);
        }
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.retention-turnover-chart', [
            'retentionData' => $this->retentionData
        ]);
    }
}