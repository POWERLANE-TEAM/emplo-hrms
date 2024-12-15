<?php

namespace App\Livewire\Employee\Overtimes;

use Livewire\Component;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Employee\Tables\AllOvertimeRequestsTable;

class SecondaryOvertimeRequestApproval extends Component
{
    /**
     * Set attributes in readable format.
     */
    #[Locked]
    public ?object $overtime;

    /**
     * Easy access to relationship and attributes.
     * 
     * @var Overtime $request
     */
    public ?Overtime $request = null;

    /** @var string|null $feedback */
    public $feedback;

    /** @var bool $isReadOnly */
    #[Locked]
    public $isReadOnly;

    /** @var bool $loading */
    public bool $loading = true;

    #[On('showPreApprovedOvertimeRequestApproval')]
    public function openOtRequest(int $overtimeId)
    {
        if ($this->request) {
            if ($this->request->overtime_id === $overtimeId) {
                $this->loading = false;
                return;
            }            
        }
        
        $this->request = Overtime::with([
            'processes',
            'processes.initialApprover',
            'processes.secondaryApprover',
            'processes.deniedBy',
            'employee',
            'employee.jobTitle',
            'employee.shift',
            'employee.jobTitle.jobLevel',
            'employee.jobTitle.jobFamily',
            'employee.account',
            'employee.status',
        ])->find($overtimeId);

        if (! $this->request) {
            $this->dispatch('modelNotFound', [
                'feedbackMsg' => __('Sorry, it seems like this record has been removed.'),
            ]);
        } else {
            $this->checkIfNotReadOnly();
            
            $this->overtime = (object) [
                'requestorPhoto'            =>  $this->request->employee->account->photo,
                'requestorName'             =>  $this->request->employee->full_name,
                'requestorFirstName'        =>  $this->request->employee->first_name,
                'requestorJobTitle'         =>  $this->request->employee->jobTitle->job_title,
                'requestorJobLevel'         =>  $this->request->employee->jobTitle->jobLevel->job_level,
                'requestorJobFamily'        =>  $this->request->employee->jobTitle->jobFamily->job_family_name,
                'requestorJobLevelName'     =>  $this->request->employee->jobTitle->jobLevel->job_level_name,
                'requestorId'               =>  $this->request->employee->employee_id,
                'requestorShift'            =>  $this->request->employee->shift->shift_name,
                'requestorShiftSched'       =>  $this->request->employee->shift_schedule,
                'requestorEmploymentStatus' =>  $this->request->employee->status->emp_status_name,
                'workToPerform'             =>  $this->request->work_performed,
                'date'                      =>  Carbon::parse($this->request->date)->format('F d, Y'),
                'startTime'                 =>  $this->request->start_time,
                'endTime'                   =>  $this->request->end_time,
                'hrsRequested'              =>  $this->request->getHoursRequested(),
                'filedAt'                   =>  $this->request->filed_at,
                'initiallyApprovedBy'       =>  $this->request?->processes?->first()?->initialApprover?->full_name,
                'initiallyApprovedAt'       =>  $this->request?->processes?->first()?->initial_approver_signed_at,
                'secondaryApprovedBy'       =>  $this->request?->processes?->first()?->secondaryApprover?->full_name,
                'secondaryApprovedAt'       =>  $this->request?->processes?->first()?->secondary_approver_signed_at,
                'deniedBy'                  =>  $this->request?->processes?->first()?->deniedBy?->full_name,
                'deniedAt'                  =>  $this->request?->processes?->first()?->denied_at,
                'feedback'                  =>  $this->request?->processes?->first()?->feedback,
            ];
        }

        $this->loading = false;
    }

    public function denyOtRequest()
    {
        $this->authorize('updateAllOvertimeRequest', Auth::user());

        $this->validate();

        DB::transaction(function () {
            $this->request->processes()->update([
                'denied_at' => now(),
                'denier' => $this->employeeId,
                'feedback' => $this->feedback,
            ]);
        });

        $this->resetPropsAndDispatchEvents();
    }

    public function approveOtRequest()
    {
        $this->authorize('updateAllOvertimeRequest', Auth::user());

        DB::transaction(function () {
            $this->request->processes()->update([
                'secondary_approver_signed_at' => now(),
                'secondary_approver' => $this->employeeId,
            ]);            
        });

        $this->resetPropsAndDispatchEvents();
    }

    private function checkIfNotReadOnly()
    {
        if (
            is_null($this->request->processes->first()->secondary_approver_signed_at) &&
            is_null($this->request->processes->first()->denied_at)
        ) {
            $this->isReadOnly = false;
        } else {
            $this->isReadOnly = true;
        }
    }

    private function resetPropsAndDispatchEvents()
    {
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved');
        $this->dispatch('changesSaved')->to(AllOvertimeRequestsTable::class);
    }

    public function rules(): array
    {
        return [
            'feedback' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'feedback.required' => __('Reason for OT request denial is required.'),
        ];
    }

    #[Computed]
    private function employeeId()
    {
        return Auth::user()->account->employee_id;
    }

    public function render()
    {
        return view('livewire.employee.overtimes.secondary-overtime-request-approval');
    }
}
