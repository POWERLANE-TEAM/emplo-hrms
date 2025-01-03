<?php

namespace App\Livewire\Employee\Issues;

use App\Models\Issue;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class IssueInfo extends Component
{
    public Issue $issue;

    #[Locked]
    public $resolution;

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
    
    public function render()
    {
        $this->resolution ??= $this->issue->given_resolution;

        return view('livewire.employee.issues.issue-info');
    }
}
