<?php

namespace App\Livewire\HrManager\Evaluations\Probationaries;

use App\Enums\PerformanceEvaluationPeriod;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PerformanceApprovals extends Component
{
    public Employee $employee;

    #[Locked]
    public $yearPeriod;

    #[Locked]
    public $routePrefix;

    #[Locked]
    public $performance;

    #[Locked]
    public $isRecommendedRegular;

    public function mount()
    {
        $this->employee->loadMissing([
            'performancesAsProbationary.details',
            'performancesAsProbationary.details.employeeEvaluator',
            'performancesAsProbationary.details.employeeEvaluator.jobTitle',
            'performancesAsProbationary.details.secondaryApprover',
            'performancesAsProbationary.details.secondaryApprover.jobTitle',
            'performancesAsProbationary.details.thirdApprover',
            'performancesAsProbationary.details.thirdApprover.jobTitle',
            'performancesAsProbationary.details.fourthApprover',
            'performancesAsProbationary.details.fourthApprover.jobTitle',
        ]);

        $this->isRecommendedRegular = $this->employee->performancesAsProbationary
            ->filter(fn ($item) => $item->period_name === PerformanceEvaluationPeriod::FINAL_MONTH->value)
            ->map(fn ($item) => $item->details->last())
            ->pluck('is_final_recommend')
            ->first();
    }

    private function makeKeysReadable()
    {
        $probationary = $this->employee->performancesAsProbationary->last()->details->last();

        return (object) [
            'details' => $probationary,
            'evaluator' => $probationary?->employeeEvaluator?->full_name,
            'evaluatorJobTitle' => $probationary?->employeeEvaluator?->jobTitle?->job_title,
            'secondaryApprover' => $probationary?->secondaryApprover?->full_name,
            'secondaryApproverJobTitle' => $probationary?->secondaryApprover?->jobTitle?->job_title,
            'secondaryApproverSignedAt' => $probationary?->secondary_approver_signed_at,
            'thirdApprover' => $probationary?->thirdApprover?->full_name,
            'thirdApproverJobTitle' => $probationary?->thirdApprover?->jobTitle?->job_title,
            'thirdApproverSignedAt' => $probationary?->third_approver_signed_at,
            'fourthApprover' => $probationary?->fourthApprover?->full_name,
            'fourthApproverJobTitle' => $probationary?->fourthApprover?->jobTitle?->job_title,
            'fourthApproverSignedAt' => $probationary?->fourth_approver_signed_at,
            'isAcknowledged' => $probationary?->is_employee_acknowledged,
            'signedAt' => $probationary?->evaluatee_signed_at,
        ];
    }

    public function markAsReceived()
    {
        DB::transaction(function () {
            $this->employee->performancesAsProbationary->each(function ($item) {
                $item->details->each(function ($subitem) {
                    $stage = '';

                    if (! $subitem->third_approver_signed_at) {
                        $this->authorize('signAnyProbationaryEvaluationForm');
                        $stage = 'third';
                    } elseif ($subitem->third_approver_signed_at) {
                        $this->authorize('signProbationaryEvaluationFormFinal');
                        $stage = 'fourth';
                    }

                    $subitem->update([
                        "{$stage}_approver" => Auth::user()->account->employee_id,
                        "{$stage}_approver_signed_at" => now(),
                    ]);
                });
            });
        });
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
        $this->performance = $this->makeKeysReadable();

        return view('livewire.hr-manager.evaluations.probationaries.performance-approvals');
    }
}
