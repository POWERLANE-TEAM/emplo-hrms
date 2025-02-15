<?php

namespace App\Livewire\HrManager\Reports;

use App\Models\Issue;
use Livewire\Component;
use App\Enums\IssueStatus;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Cache;

class IssueResolutionChart extends Component
{
    public $year;

    #[Locked]
    public $issueResolutionData = [];

    #[Locked]
    public $yearlyData = [];

    #[Locked]
    public $monthlyData = [];

    public function mount()
    {
        $key = sprintf(config('cache.keys.reports.issue_resolution_time_rate'), $this->year);

        $this->issueResolutionData  = Cache::get($key);

        if ($this->issueResolutionData) {
            $this->monthlyData = $this->issueResolutionData['monthly'];
            $this->yearlyData = $this->issueResolutionData['yearly'];
            return;
        }

        $issues = Issue::whereYear('filed_at', $this->year)
            ->get()
            ->filter(function ($issue) {
                return in_array($issue->status, [
                    IssueStatus::RESOLVED->value,
                    IssueStatus::CLOSED->value
                ]);
            })
            ->map(function ($issue) {
                return [
                    'date_submitted' => $issue->filed_at,
                    'date_resolved' => $issue->status_marked_at,
                ];
            })->toArray();


        foreach ($issues as $issue) {
            $dateSubmitted = strtotime($issue['date_submitted']);
            $dateResolved = strtotime($issue['date_resolved']);

            $resolvedDays = ($dateResolved - $dateSubmitted) / 86400;

            $issue['resolved_days'] = $resolvedDays;

            $issue['resolved_date'] = date('Y-m-d', $dateResolved);

            $year = date('Y', $dateResolved);
            $month = date('Y-m', $dateResolved);

            if (!isset($this->yearlyData[$year])) {
                $this->yearlyData[$year] = ['total_days' => 0, 'count' => 0];
            }
            $this->yearlyData[$year]['total_days'] += $resolvedDays;
            $this->yearlyData[$year]['count']++;

            if (!isset($this->monthlyData[$month])) {
                $this->monthlyData[$month] = ['total_days' => 0, 'count' => 0];
            }
            $this->monthlyData[$month]['total_days'] += $resolvedDays;
            $this->monthlyData[$month]['count']++;
        }

        foreach ($this->yearlyData as $year => &$data) {
            if ($data['count'] > 0) {
                $data['average_days'] = $data['total_days'] / $data['count'];
            } else {
                $data['average_days'] = 0;
            }
        }

        foreach ($this->monthlyData as $month => &$data) {
            if ($data['count'] > 0) {
                $data['average_days'] = $data['total_days'] / $data['count'];
            } else {
                $data['average_days'] = 0;
            }
        }

        $this->issueResolutionData = [
            'yearly' => $this->yearlyData,
            'monthly' => $this->monthlyData,
        ];

        Cache::forever($key, $this->issueResolutionData);
    }

    public function render()
    {   
        return view('livewire.hr-manager.reports.issue-resolution-chart');
    }
}
