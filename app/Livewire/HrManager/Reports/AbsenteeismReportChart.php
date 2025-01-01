<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class AbsenteeismReportChart extends Component
{

    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 1. 'month': The current month.
     * 3. 'absences': Total count of "absent" value in "date" column per month.
     * 
     * 4. After fetching, replace/populate the $data in mount() function.
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * 
     */


    public $selectedYear;

    public $absenteeismData;
    public $yearlyData;
    public $monthlyData;

    public function mount()
    {
        $this->selectedYear;
        logger('ABSENTEEISM CHART - Selected Year initialized to: ' . $this->selectedYear);

        $data = [
            ['month' => '2024-01', 'absences' => 4],
            ['month' => '2024-02', 'absences' => 10],
            ['month' => '2024-03', 'absences' => 6],
            ['month' => '2024-04', 'absences' => 8],
            ['month' => '2024-05', 'absences' => 5],
            ['month' => '2024-06', 'absences' => 7],
            ['month' => '2024-07', 'absences' => 9],
            ['month' => '2024-08', 'absences' => 3],
            ['month' => '2024-09', 'absences' => 6],
            ['month' => '2024-10', 'absences' => 5],
            ['month' => '2024-11', 'absences' => 8],
            ['month' => '2024-12', 'absences' => 7]
        ];

        $this->yearlyData = [];
        $this->monthlyData = [];

        foreach ($data as $record) {
            $year = substr($record['month'], 0, 4);
            $month = $record['month'];

            if (!isset($this->yearlyData[$year])) {
                $this->yearlyData[$year] = ['total_absences' => 0];
            }
            $this->yearlyData[$year]['total_absences'] += $record['absences'];

            $this->monthlyData[$month] = ['absences' => $record['absences']];
        }

        // Calculate yearly average
        foreach ($this->yearlyData as $year => &$data) {
            $data['monthly_average'] = $data['total_absences'] / 12;
        }

        // Pass the data to the view
        $this->absenteeismData = [
            'yearly' => $this->yearlyData,
            'monthly' => $this->monthlyData,
        ];
    }

    public function updated($name)
    {
        if ($name === 'selectedYear' && !empty($this->selectedYear)) {
            logger('ABSENTEEISM CHART - Selected Year updated to: ' . $this->selectedYear);
        }
    }

    public function render()
    {   
        return view('livewire.hr-manager.reports.absenteeism-report-chart', [
            'absenteeismData' => $this->absenteeismData,
            'yearlyData' => $this->yearlyData,
            'monthlyData' => $this->monthlyData
        ]);
    }
}
