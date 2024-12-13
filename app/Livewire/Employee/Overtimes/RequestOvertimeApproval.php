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
    private Overtime $request;

    /** @var string|null $feedback */
    public $feedback;

    /** @var bool $isReadOnly */
    #[Locked]
    public $isReadOnly;
    
    #[On('findModelId')]
    public function openOtRequest(int $overtimeId)
    {
        $this->request = Overtime::with([
            'processes',
            'employee',
            'employee.jobTitle',
            'employee.shift',
            'employee.jobTitle.jobLevel',
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
            ];
        }

        $this->dispatch('openOvertimeRequestApprovalModal');
    }

    public function denyOtRequest()
    {
        $this->authorize('updateSubordinateOvertimeRequest', Auth::user());

        $this->validate();

        DB::transaction(function () {
            $this->request->processes()->update([
                'denied_at' => now(),
                'denier' => $this->employeeId,
                'feedback' => $this->feedback,
            ]);
        });

        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved');
    }

    public function approveOtRequest()
    {
        $this->authorize('updateSubordinateOvertimeRequest', Auth::user());

        DB::transaction(function () {
            $this->request->processes()->update([
                'initial_approver_signed_at' => now(),
                'initial_approver' => $this->employeeId,
            ]);            
        });

        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved');
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
