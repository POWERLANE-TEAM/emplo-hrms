<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;

class KeyMetrics extends Component
{
    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 1. Fetch incidents, issues, training.
     * 2. 'completed': Total count of rows with completed/resolved/etc value.
     * 3. 'total': Total count each incidents, issues, training.
     * 
     * 4. After fetching, replace/populate the $data in mount() function.
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * 
     */

    public $metrics;

    public function mount()
    {
        // Sample data - replace with actual database queries
        $data = [
            'incidents' => [
                'completed' => 8,
                'total' => 19
            ],
            'issues' => [
                'completed' => 34,
                'total' => 34
            ],
            'training' => [
                'completed' => 11,
                'total' => 19
            ]
        ];

        // Calculate percentages and prepare metrics
        $this->metrics = [
            'incidents' => [
                'type' => 'Incidents',
                'completed' => $data['incidents']['completed'],
                'total' => $data['incidents']['total'],
                'percentage' => $this->calculatePercentage(
                    $data['incidents']['completed'],
                    $data['incidents']['total']
                )
            ],
            'issues' => [
                'type' => 'Issues',
                'completed' => $data['issues']['completed'],
                'total' => $data['issues']['total'],
                'percentage' => $this->calculatePercentage(
                    $data['issues']['completed'],
                    $data['issues']['total']
                )
            ],
            'training' => [
                'type' => 'Training',
                'completed' => $data['training']['completed'],
                'total' => $data['training']['total'],
                'percentage' => $this->calculatePercentage(
                    $data['training']['completed'],
                    $data['training']['total']
                )
            ]
        ];
    }

    private function calculatePercentage($completed, $total)
    {
        if ($total == 0) return 0;
        return round(($completed / $total) * 100);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.key-metrics', [
            'metrics' => $this->metrics
        ]);
    }
}