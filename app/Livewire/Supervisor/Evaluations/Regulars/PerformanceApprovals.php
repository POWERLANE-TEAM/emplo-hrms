<?php

namespace App\Livewire\Supervisor\Evaluations\Regulars;

use App\Models\Employee;
use App\Models\RegularPerformance;
use Illuminate\Support\Facades\Auth;
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

    public function markAsReceived()
    {
        $this->authorize('signRegularSubordinateEvaluationForm');

        $this->performance->secondary_approver = Auth::user()->account->employee_id;
        $this->performance->secondary_approver_signed_at = now();

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
        $this->performance;

        return view('livewire.supervisor.evaluations.regulars.performance-approvals');
    }
}
