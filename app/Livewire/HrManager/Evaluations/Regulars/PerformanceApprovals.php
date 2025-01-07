<?php

namespace App\Livewire\HrManager\Evaluations\Regulars;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use App\Models\RegularPerformance;
use Illuminate\Support\Facades\Auth;

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
        if (! $this->performance->third_approver_signed_at) {

            $this->authorize('signAnyRegularEvaluationForm');

            $this->performance->third_approver = Auth::user()->account->employee_id;
            $this->performance->third_approver_signed_at = now();

            $this->performance->save();

        } elseif ($this->performance->third_approver_signed_at) {

            $this->authorize('signRegularEvaluationFormFinal');

            $this->performance->fourth_approver = Auth::user()->account->employee_id;
            $this->performance->fourth_approver_signed_at = now();

            $this->performance->save();
        }
    }

    #[Computed]
    public function hrdManager()
    {
        return Employee::whereHas('jobTitle', function ($query) {
            $query->whereLike('job_title', "%hrd manager%");
        })?->first()?->full_name;
    }

    #[Computed]
    public function randomHrdStaff()
    {
        return Employee::whereHas('jobTitle', function ($query) {
            $query->whereLike('job_title', "%hr staff%");
        })?->first()?->full_name;
    }

    public function render()
    {
        return view('livewire.hr-manager.evaluations.regulars.performance-approvals');
    }
}
