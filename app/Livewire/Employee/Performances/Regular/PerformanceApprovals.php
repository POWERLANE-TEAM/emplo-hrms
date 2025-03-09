<?php

namespace App\Livewire\Employee\Performances\Regular;

use App\Models\Employee;
use App\Models\RegularPerformance;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PerformanceApprovals extends Component
{
    public RegularPerformance $performance;

    #[Locked]
    public $routePrefix;

    #[Locked]
    public $finalRating;

    public $comments;

    public function mount()
    {
        $this->finalRating = $this->performance->final_rating;

        $this->performance->loadMissing([
            'employeeEvaluator',
            'employeeEvaluator.jobTitle',
            'secondaryApprover',
            'secondaryApprover.jobTitle',
            'thirdApprover',
            'thirdApprover.jobTitle',
            'fourthApprover',
            'fourthApprover.jobTitle',
        ]);
    }

    public function markAsAcknowledged()
    {
        // authorize or maybe not

        $this->performance->evaluatee_comments = $this->comments;
        $this->performance->evaluatee_signed_at = now();
        $this->performance->is_employee_acknowledged = true;

        $this->performance->save();
    }

    #[Computed]
    public function hrdManager()
    {
        return Employee::whereHas('jobTitle', function ($query) {
            $query->whereLike('job_title', '%hrd manager%');
        })?->first()?->full_name;
    }

    #[Computed]
    public function randomHrdStaff()
    {
        return Employee::whereHas('jobTitle', function ($query) {
            $query->whereLike('job_title', '%hr staff%');
        })?->first()?->full_name;
    }

    public function render()
    {
        return view('livewire.employee.performances.regular.performance-approvals');
    }
}
