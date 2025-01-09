<?php

namespace App\Livewire\HrManager\Issues;

use App\Models\Issue;
use Livewire\Component;
use App\Enums\IssueStatus;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IssueInfo extends Component
{
    public Issue $issue;

    #[Locked]
    public $routePrefix;

    public $resolution;

    public function mount()
    {
        $this->issue->loadMissing([
            'reporter',
            'reporter.account',
            'statusMarker',
            'statusMarker.account',
            'statusMarker.shift',
            'statusMarker.jobTitle',
            'statusMarker.jobTitle.jobLevel'
        ]);
    }
    
    public function closeIssue()
    {
        $this->updateIssueInformation(
            IssueStatus::CLOSED->value,
            IssueStatus::CLOSED->getLabel(),
        );
    }

    public function markIssueResolve()
    {
        $this->updateIssueInformation(
            IssueStatus::RESOLVED->value,
            IssueStatus::RESOLVED->getLabel(),
        );
    }

    private function updateIssueInformation(int $status, string $statusLabel)
    {
        $this->authorize('updateIssueStatus');

        $this->validate();

        DB::transaction(function () use ($status) {
            $this->issue->update([
                'status'            => $status,
                'status_marker'     => Auth::user()->account->employee_id,
                'status_marked_at'  => now(),
                'given_resolution'  => $this->resolution,
            ]);
        });

        $this->dispatch('updatedIssueStatus', [
            'type' => 'success',
            'message' => __("{$this->issue->reporter->last_name}'s report status marked as {$statusLabel}."),
        ]);

        $this->resetExcept('issue', 'routePrefix');
    }

    #[Computed]
    public function statusMarker()
    {
        $marker = $this->issue?->statusMarker;
        $isMe = $marker->account->is(Auth::user());

        return (object) [
            'name'          => $isMe ? __("{$marker->full_name} (You)") : $marker->full_name,
            'photo'         => $marker?->account?->photo,
            'jobTitle'      => $marker?->jobTitle?->job_title,
            'jobLevel'      => $marker?->jobTitle?->jobLevel?->job_level,
            'jobLevelName'  => $marker?->jobTitle?->jobLevel?->job_level_name,
            'employeeId'    => $marker?->employee_id,
            'shift'         => $marker?->shift?->shift_name,
            'shiftSchedule' => $marker?->shift_schedule,
            'employment'    => $marker?->status?->emp_status_name,
        ];
    }

    public function rules(): array
    {
        return [
            'resolution' => 'required|string'
        ];
    }

    public function render()
    {
        $this->resolution ??= $this->issue->given_resolution;
        
        return view('livewire.hr-manager.issues.issue-info');
    }
}
