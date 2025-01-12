<?php

namespace App\Livewire\Employee\Performances\Probationary;

use Livewire\Component;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Enums\PerformanceEvaluationPeriod;

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

    public $comments;

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
            'evaluator'                 => $probationary?->employeeEvaluator?->full_name,
            'evaluatorJobTitle'         => $probationary?->employeeEvaluator?->jobTitle?->job_title,
            'secondaryApprover'         => $probationary?->secondaryApprover?->full_name,
            'secondaryApproverJobTitle' => $probationary?->secondaryApprover?->jobTitle?->job_title,
            'secondaryApproverSignedAt' => $probationary?->secondary_approver_signed_at,
            'thirdApprover'             => $probationary?->thirdApprover?->full_name,
            'thirdApproverJobTitle'     => $probationary?->thirdApprover?->jobTitle?->job_title,
            'thirdApproverSignedAt'     => $probationary?->third_approver_signed_at,
            'fourthApprover'            => $probationary?->fourthApprover?->full_name,
            'fourthApproverJobTitle'    => $probationary?->fourthApprover?->jobTitle?->job_title,
            'fourthApproverSignedAt'    => $probationary?->fourth_approver_signed_at,
            'isAcknowledged'            => $probationary?->is_employee_acknowledged,
            'comments'                  => $probationary?->evaluatee_comments,
            'signedAt'                  => Carbon::make($probationary?->evaluatee_signed_at)?->format('F d, Y g:i A') ?? null,
        ];
    }

    public function markAsAcknowledged()
    {
        // this runs 3 queries of update for each model. Idk if that's expensive
        DB::transaction(function () {
            $this->employee->performancesAsProbationary->each(function ($item) {
                $item->details->each(function ($subitem) {
                    $subitem->update([
                        'evaluatee_comments' => $this->comments,
                        'evaluatee_signed_at' => now(),
                        'is_employee_acknowledged' => true,
                    ]);                    
                });
            });
        });
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
        $this->performance = $this->makeKeysReadable();

        return view('livewire.employee.performances.probationary.performance-approvals');
    }
}
