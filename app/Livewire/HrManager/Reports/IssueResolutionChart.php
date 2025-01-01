<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class IssueResolutionChart extends Component
{

    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 1. Fetch all of the completed issues' columns: date_submitted & date_completed.
     * 2. After fetching, replace/populate the $data in mount() function.
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * 
     */

    public $selectedYear;

    public $issueResolutionData;
    public $yearlyData;
    public $monthlyData;

    public function mount()
    {

        $this->selectedYear;
        logger('ISSUE CHART - Selected Year initialized to: ' . $this->selectedYear);

        // Placeholder data for issue resolution times
        $data = [
            // January
            [
                'date_submitted' => '2024-01-01 09:00:00',
                'date_resolved' => '2024-01-03 15:00:00',
            ],
            [
                'date_submitted' => '2024-01-10 11:00:00',
                'date_resolved' => '2024-01-12 17:30:00',
            ],
            [
                'date_submitted' => '2024-01-15 08:45:00',
                'date_resolved' => '2024-01-17 14:30:00',
            ],

            // February
            [
                'date_submitted' => '2024-02-01 10:00:00',
                'date_resolved' => '2024-02-03 16:00:00',
            ],
            [
                'date_submitted' => '2024-02-08 09:30:00',
                'date_resolved' => '2024-02-10 18:00:00',
            ],
            [
                'date_submitted' => '2024-02-15 14:00:00',
                'date_resolved' => '2024-02-17 12:00:00',
            ],

            // March
            [
                'date_submitted' => '2024-03-02 13:30:00',
                'date_resolved' => '2024-03-04 16:00:00',
            ],
            [
                'date_submitted' => '2024-03-10 09:00:00',
                'date_resolved' => '2024-03-12 11:00:00',
            ],
            [
                'date_submitted' => '2024-03-20 16:30:00',
                'date_resolved' => '2024-03-22 13:00:00',
            ],

            // April
            [
                'date_submitted' => '2024-04-05 10:00:00',
                'date_resolved' => '2024-04-07 17:30:00',
            ],
            [
                'date_submitted' => '2024-04-12 12:00:00',
                'date_resolved' => '2024-04-14 15:00:00',
            ],
            [
                'date_submitted' => '2024-04-20 08:30:00',
                'date_resolved' => '2024-04-22 13:15:00',
            ],

            // May
            [
                'date_submitted' => '2024-05-01 08:00:00',
                'date_resolved' => '2024-05-03 14:00:00',
            ],
            [
                'date_submitted' => '2024-05-10 09:45:00',
                'date_resolved' => '2024-05-12 16:30:00',
            ],
            [
                'date_submitted' => '2024-05-15 14:30:00',
                'date_resolved' => '2024-05-17 17:00:00',
            ],

            // June
            [
                'date_submitted' => '2024-06-03 10:00:00',
                'date_resolved' => '2024-06-05 12:00:00',
            ],
            [
                'date_submitted' => '2024-06-12 09:00:00',
                'date_resolved' => '2024-06-14 11:00:00',
            ],
            [
                'date_submitted' => '2024-06-20 15:45:00',
                'date_resolved' => '2024-06-22 18:30:00',
            ],

            // July
            [
                'date_submitted' => '2024-07-01 08:30:00',
                'date_resolved' => '2024-07-03 10:00:00',
            ],
            [
                'date_submitted' => '2024-07-08 10:30:00',
                'date_resolved' => '2024-07-10 12:30:00',
            ],
            [
                'date_submitted' => '2024-07-15 11:00:00',
                'date_resolved' => '2024-07-17 14:00:00',
            ],

            // August
            [
                'date_submitted' => '2024-08-04 13:00:00',
                'date_resolved' => '2024-08-06 15:00:00',
            ],
            [
                'date_submitted' => '2024-08-12 10:30:00',
                'date_resolved' => '2024-08-14 11:30:00',
            ],
            [
                'date_submitted' => '2024-08-20 16:00:00',
                'date_resolved' => '2024-08-22 18:00:00',
            ],

            // September
            [
                'date_submitted' => '2024-09-03 09:00:00',
                'date_resolved' => '2024-09-05 12:00:00',
            ],
            [
                'date_submitted' => '2024-09-10 14:00:00',
                'date_resolved' => '2024-09-12 16:00:00',
            ],
            [
                'date_submitted' => '2024-09-18 11:30:00',
                'date_resolved' => '2024-09-20 13:00:00',
            ],

            // October
            [
                'date_submitted' => '2024-10-01 10:00:00',
                'date_resolved' => '2024-10-03 14:00:00',
            ],
            [
                'date_submitted' => '2024-10-05 12:30:00',
                'date_resolved' => '2024-10-07 15:30:00',
            ],
            [
                'date_submitted' => '2024-10-12 09:00:00',
                'date_resolved' => '2024-10-14 11:30:00',
            ],

            // November
            [
                'date_submitted' => '2024-11-02 10:00:00',
                'date_resolved' => '2024-11-04 12:30:00',
            ],
            [
                'date_submitted' => '2024-11-09 14:00:00',
                'date_resolved' => '2024-11-11 16:30:00',
            ],
            [
                'date_submitted' => '2024-11-15 13:00:00',
                'date_resolved' => '2024-11-17 17:00:00',
            ],

            // December
            [
                'date_submitted' => '2024-12-01 11:00:00',
                'date_resolved' => '2024-12-03 15:00:00',
            ],
            [
                'date_submitted' => '2024-12-07 10:30:00',
                'date_resolved' => '2024-12-09 12:45:00',
            ],
            [
                'date_submitted' => '2024-12-14 08:15:00',
                'date_resolved' => '2024-12-16 16:00:00',
            ],
        ];
        // Initialize arrays for storing yearly and monthly data
        $this->yearlyData = [];
        $this->monthlyData = [];

        // Process the data
        foreach ($data as $issue) {
            $dateSubmitted = strtotime($issue['date_submitted']);
            $dateResolved = strtotime($issue['date_resolved']);
            // Calculate the difference in days
            $resolvedDays = ($dateResolved - $dateSubmitted) / 86400; // 86400 seconds in a day
            // Add resolved days to the issue data
            $issue['resolved_days'] = $resolvedDays;
            // Format resolved date as a string
            $issue['resolved_date'] = date('Y-m-d', $dateResolved);
            // Extract year and month
            $year = date('Y', $dateResolved);
            $month = date('Y-m', $dateResolved); // 'YYYY-MM' format for monthly breakdown

            // Sum up the days by year and increment the issue count
            if (!isset($this->yearlyData[$year])) {
                $this->yearlyData[$year] = ['total_days' => 0, 'count' => 0];
            }
            $this->yearlyData[$year]['total_days'] += $resolvedDays;
            $this->yearlyData[$year]['count']++;

            // Sum up the days by month and increment the issue count
            if (!isset($this->monthlyData[$month])) {
                $this->monthlyData[$month] = ['total_days' => 0, 'count' => 0];
            }
            $this->monthlyData[$month]['total_days'] += $resolvedDays;
            $this->monthlyData[$month]['count']++;
        }

        // Calculate average resolution time by year
        foreach ($this->yearlyData as $year => &$data) {
            if ($data['count'] > 0) {
                $data['average_days'] = $data['total_days'] / $data['count'];
            } else {
                $data['average_days'] = 0;
            }
        }

        // Calculate average resolution time by month
        foreach ($this->monthlyData as $month => &$data) {
            if ($data['count'] > 0) {
                $data['average_days'] = $data['total_days'] / $data['count'];
            } else {
                $data['average_days'] = 0;
            }
        }

        // Pass the data to the view
        $this->issueResolutionData = [
            'yearly' => $this->yearlyData,
            'monthly' => $this->monthlyData,
        ];
    }

    public function updated($name)
    {
        if ($name === 'selectedYear' && !empty($this->selectedYear)) {
            logger('ISSUE CHART - Selected Year updated to: ' . $this->selectedYear);
        }
    }

    public function render()
    {   
        return view('livewire.hr-manager.reports.issue-resolution-chart', [
            'issueResolutionData' => $this->issueResolutionData,
            'yearlyData' => $this->yearlyData,
            'monthlyData' => $this->monthlyData
        ]);
    }
}
