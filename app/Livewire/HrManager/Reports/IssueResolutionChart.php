<?php

namespace App\Livewire\HrManager\Reports;

use App\Enums\IssueStatus;
use App\Models\Issue;
use Livewire\Component;

class IssueResolutionChart extends Component
{
    public $year;

    public $issueResolutionData = [];

    public $yearlyData = [];

    public $monthlyData = [];

    public function mount()
    {
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
