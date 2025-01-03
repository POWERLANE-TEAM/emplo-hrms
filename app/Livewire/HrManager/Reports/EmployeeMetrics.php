<?php

namespace App\Livewire\HrManager\Reports;
use \Carbon\Carbon;
use Livewire\Component;

class EmployeeMetrics extends Component
{

    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 
     * In calculateEmployeeTenure()
     * 1. 'date_start': Date start of ALL employees.
     * 2. Fetch all of the rows.
     * 
     * In calculateNewHires()
     * 1. 'hires':  All of the applicants that has been hired.
     *              This can be based if their start_date is on the selected year.
     * 2. 'applicants':  Total count of applicants in that year.
     * 
     * In calculateEvaluationSuccess()
     * 1. 'passed': Total count of rows of regular employees that passed on their annual evaluation.
     * 2. 'total': Total number of regular employees.
     * 
     * ► After fetching, replace the placeholder datas.
     * ► This just needs proper fetching from the database. The logic is already implemented.
     * 
     */
    public $selectedYear;
    public $metrics = [];

    public function mount()
    {
        $this->selectedYear;

        $this->metrics = [
            'employee_tenure' => $this->calculateEmployeeTenure(),
            'new_hires' => $this->calculateNewHires(),
            'evaluation_success' => $this->calculateEvaluationSuccess(),
        ];
    }

    private function calculateEmployeeTenure()
    {
        $employees = [
            ['date_start' => '2020-01-15'],
            ['date_start' => '2018-06-01'],
            ['date_start' => '2022-03-20'],
        ];
    
        $totalTenure = 0;
        $currentDate = Carbon::now();
    
        foreach ($employees as $employee) {
            $startDate = Carbon::parse($employee['date_start']);
            $tenure = abs($currentDate->diffInDays($startDate)) / 365.25;
    
            if ($tenure > 0) {
                $totalTenure += $tenure;
            }
        }
    
        return round($totalTenure / count($employees), 1); // Average tenure in years
    }

    private function calculateNewHires()
    {
        $hires = 8; // Example value
        $applicants = 40; // Example value

        return [
            'hires' => $hires,
            'applicants' => $applicants,
        ];
    }

    private function calculateEvaluationSuccess()
    {
        $passed = 45; // Example count of passed employees
        $total = 100; // Example total employees evaluated

        return round(($passed / $total) * 100, 1); // Passing rate in percentage
    }

    public function updated($name)
    {
        if ($name === 'selectedYear' && !empty($this->selectedYear)) {
            logger('EMPLOYEE METRICS - Selected Year updated to: ' . $this->selectedYear);
        }
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.employee-metrics', [
            'metrics' => $this->metrics,
        ]);
    }
}
