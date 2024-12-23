<?php

namespace App\Livewire\Employee\Overtimes;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Employee\Tables\OvertimeRequestsTable;
use App\Livewire\Employee\Tables\AllOvertimeRequestsTable;

/**
 * This belongs to initial approver. Don't confuse this as the final approver.
 */
class RequestOvertimeApproval extends Component
{
    /**
     * Set attributes in readable format.
     */
    #[Locked]
    public $overtime;

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
    
    #[On('showOvertimeRequestApproval')]
    public function openOtRequest(int $overtimeId)
    {
        $this->request = Overtime::with([
            'authorizedBy',
            'deniedBy',
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
                'feedbackMsg' => __('Sorry, it seems like this record has already been removed.'),
            ]);
        } else {
            $this->checkIfNotReadOnly();
            
            $this->overtime = (object) [
                'requestorPhoto'            =>  $this->request->employee->account->photo,
                'requestorName'             =>  $this->request->employee->full_name,
                'requestorFirstName'        =>  $this->request->employee->first_name,
                'requestorJobTitle'         =>  $this->request->employee->jobTitle->job_title,
                'requestorJobLevel'         =>  $this->request->employee->jobTitle->jobLevel->job_level,
                'requestorJobLevelName'     =>  $this->request->employee->jobTitle->jobLevel->job_level_name,
                'requestorId'               =>  $this->request->employee->employee_id,
                'requestorShift'            =>  $this->request->employee->shift->shift_name,
                'requestorShiftSched'       =>  $this->request->employee->shift_schedule,
                'requestorEmploymentStatus' =>  $this->request->employee->status->emp_status_name,
                'workToPerform'             =>  $this->request->work_performed,
                'date'                      =>  Carbon::parse($this->request->date)->format('F d, Y'),
                'startTime'                 =>  $this->request->start_time,
                'endTime'                   =>  $this->request->end_time,
                'hrsRequested'              =>  $this->request->hoursRequested,
                'filedAt'                   =>  $this->request->filed_at,
                'authorizedBy'              =>  $this->checkIfSameAsAuthUser($this->request?->authorizedBy),
                'authorizedAt'              =>  $this->request?->authorizer_signed_at,
                'deniedBy'                  =>  $this->checkIfSameAsAuthUser($this->request?->deniedBy),
                'deniedAt'                  =>  $this->request?->denied_at,
                'feedback'                  =>  $this->request?->feedback,
            ];
        }

        $this->loading = false;
    }

    public function denyOtRequest()
    {
        $this->authorize('authorizeOvertimeRequest', Auth::user());

        $this->validate();

        DB::transaction(function () {
            $this->request->update([
                'denied_at' => now(),
                'denier' => $this->employeeId,
                'feedback' => $this->feedback,
            ]);
        });

        $this->resetPropsAndDispatchEvents();
    }

    public function approveOtRequest()
    {
        $this->authorize('authorizeOvertimeRequest', Auth::user());

        DB::transaction(function () {
            $this->request->update([
                'authorizer_signed_at' => now(),
                'authorizer' => $this->employeeId,
            ]);            
        });

        $this->resetPropsAndDispatchEvents();
    }

    private function checkIfNotReadOnly()
    {
        if (
            is_null($this->request->authorizer_signed_at) &&
            is_null($this->request->denied_at)
        ) {
            $this->isReadOnly = false;
        } else {
            $this->isReadOnly = true;
        }
    }

    private function checkIfSameAsAuthUser(?Employee $employee)
    {
        return $employee?->employee_id === Auth::user()->account->employee_id
            ? "{$employee?->full_name} (You)"
            : $employee?->full_name;
    }

    private function resetPropsAndDispatchEvents()
    {
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved');
        $this->dispatch('changesSaved')->to(AllOvertimeRequestsTable::class);
        $this->dispatch('changesSaved')->to(OvertimeRequestsTable::class);
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
        return view('livewire.employee.overtimes.request-overtime-approval');
    }
}
