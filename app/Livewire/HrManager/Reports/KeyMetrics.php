<?php

namespace App\Livewire\HrManager\Reports;

use App\Models\Issue;
use Livewire\Component;
use App\Models\Incident;
use App\Models\Training;
use App\Enums\IssueStatus;
use App\Enums\TrainingStatus;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\Cache;

class KeyMetrics extends Component
{
    #[Reactive]
    public $year;

    public $metrics;

    // this gotta be the most awful shit i laid my eyes upon.
    public function mount()
    {
        $key = sprintf(config('cache.keys.reports.key_metrics'), $this->year);

        $this->metrics = Cache::get($key);

        if ($this->metrics) return;

        $incidents = Incident::whereYear('created_at', $this->year)
            ->get()
            ->map(function ($incident) {
                $completed = in_array($incident->status, [IssueStatus::OPEN->value, IssueStatus::CLOSED->value]);

                return [
                    'completed' => $completed ? 1 : 0,
                    'total' => 1,
                ];
            });

        $issues = Issue::whereYear('filed_at', $this->year)
            ->get()
            ->map(function ($issue) {
                $completed = in_array($issue->status, [IssueStatus::OPEN->value, IssueStatus::CLOSED->value]);

                return [
                    'completed' => $completed ? 1 : 0,
                    'total' => 1,
                ];
            });

        $trainings = Training::whereYear('created_at', $this->year)
            ->get()
            ->map(function ($training) {
                return [
                    'completed' => $training->completion_status === TrainingStatus::COMPLETED->value ? 1 : 0,
                    'total' => 1,
                ];
            });

    
        $data = [
            'incidents' => [
                'completed' => $incidents->sum('completed'),
                'total' => $incidents->count(),
            ],
            'issues' => [
                'completed' => $issues->sum('completed'),
                'total' => $issues->count(),
            ],
            'training' => [
                'completed' => $trainings->sum('completed'),
                'total' => $trainings->count(),
            ]
        ];

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

        Cache::forever($key, $this->metrics);
    }

    private function calculatePercentage($completed, $total)
    {
        if ($total == 0)
            return 0;
        return round(($completed / $total) * 100);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.key-metrics');
    }
}