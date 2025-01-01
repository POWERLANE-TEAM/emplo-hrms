<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class AverageAttendanceChart extends Component
{

    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 1. 'month': The current month.
     * 1. 'workdays': Total count of work days in a month. (Exclude the weekends, holidays)
     * 2. 'total_employees': Total employees.
     * 3. 'days_attended': Total count of "present" value in "date" column per month.
     * 
     * 4. After fetching, replace/populate the $data in mount() function.
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * 
     */

    public $selectedYear;

    public $attendanceData;
    public $yearlyData;
    public $monthlyData;

    public function mount()
    {

        $this->selectedYear;

        $data = [
            [
                'month' => '2024-01',
                'total_employees' => 50,
                'workdays' => 22,
                'days_attended' => 1078 // Example: Out of 1100 possible days (50 employees × 22 days)
            ],
            [
                'month' => '2024-02',
                'total_employees' => 50,
                'workdays' => 20,
                'days_attended' => 990  // Out of 1000 possible days
            ],
            [
                'month' => '2024-03',
                'total_employees' => 50,
                'workdays' => 21,
                'days_attended' => 1029 // Out of 1050 possible days
            ],
            [
                'month' => '2024-04',
                'total_employees' => 50,
                'workdays' => 22,
                'days_attended' => 1056 // Out of 1100 possible days
            ],
            [
                'month' => '2024-05',
                'total_employees' => 50,
                'workdays' => 23,
                'days_attended' => 1127 // Out of 1150 possible days
            ],
            [
                'month' => '2024-06',
                'total_employees' => 50,
                'workdays' => 20,
                'days_attended' => 980  // Out of 1000 possible days
            ],
            [
                'month' => '2024-07',
                'total_employees' => 50,
                'workdays' => 23,
                'days_attended' => 1104 // Out of 1150 possible days
            ],
            [
                'month' => '2024-08',
                'total_employees' => 50,
                'workdays' => 22,
                'days_attended' => 1078 // Out of 1100 possible days
            ],
            [
                'month' => '2024-09',
                'total_employees' => 50,
                'workdays' => 21,
                'days_attended' => 1029 // Out of 1050 possible days
            ],
            [
                'month' => '2024-10',
                'total_employees' => 50,
                'workdays' => 23,
                'days_attended' => 1127 // Out of 1150 possible days
            ],
            [
                'month' => '2024-11',
                'total_employees' => 50,
                'workdays' => 21,
                'days_attended' => 1019 // Out of 1050 possible days
            ],
            [
                'month' => '2024-12',
                'total_employees' => 50,
                'workdays' => 19,
                'days_attended' => 931  // Out of 950 possible days
            ],
        ];

        // Initialize arrays for storing yearly and monthly data
        $this->yearlyData = [];
        $this->monthlyData = [];

        $yearlyTotalAttended = 0;
        $yearlyTotalScheduled = 0;

        // Process the data
        foreach ($data as $record) {
            $year = substr($record['month'], 0, 4);
            $month = $record['month'];

            // Calculate total scheduled workdays for the month
            $totalScheduledDays = $record['total_employees'] * $record['workdays'];
            
            // Calculate attendance rate for the month
            $attendanceRate = ($record['days_attended'] / $totalScheduledDays) * 100;

            // Store monthly data
            $this->monthlyData[$month] = [
                'attendance_rate' => round($attendanceRate, 2),
                'days_attended' => $record['days_attended'],
                'total_scheduled' => $totalScheduledDays,
                'workdays' => $record['workdays'],
                'total_employees' => $record['total_employees']
            ];

            // Accumulate yearly totals
            $yearlyTotalAttended += $record['days_attended'];
            $yearlyTotalScheduled += $totalScheduledDays;
        }

        // Calculate yearly attendance rate
        $this->yearlyData[$year] = [
            'attendance_rate' => round(($yearlyTotalAttended / $yearlyTotalScheduled) * 100, 2),
            'total_days_attended' => $yearlyTotalAttended,
            'total_scheduled_days' => $yearlyTotalScheduled
        ];

        // Pass the data to the view
        $this->attendanceData = [
            'yearly' => $this->yearlyData,
            'monthly' => $this->monthlyData,
        ];
    }

    
    public function updated($name)
    {
        if ($name === 'selectedYear' && !empty($this->selectedYear)) {
            logger('ATTENDANCE CHART - Selected Year updated to: ' . $this->selectedYear);
        }
    }

    public function render()
    {   
        return view('livewire.hr-manager.reports.average-attendance-chart', [
            'attendanceData' => $this->attendanceData,
            'yearlyData' => $this->yearlyData,
            'monthlyData' => $this->monthlyData
        ]);
    }
}