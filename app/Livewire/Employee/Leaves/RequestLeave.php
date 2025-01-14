<?php

namespace App\Livewire\Employee\Leaves;

use App\Enums\Sex;
use App\Enums\FilePath;
use Livewire\Component;
use App\Models\EmployeeLeave;
use App\Models\LeaveCategory;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestLeave extends Component
{
    use WithFileUploads;

    #[Validate]
    public $state = [
        'leaveType' => null,
        'startDate' => null,
        'endDate' => null,
        'reason' => '',
    ];

    public $disabledEndDate = false;

    public $showLeaveDescription = false;

    public $attachments = [];

    #[Locked]
    public $totalDaysLeave = 0;

    public function updated($prop)
    {
        if (
            in_array($prop, ['state.startDate', 'state.endDate']) &&
            isset($this->state['startDate'], $this->state['endDate'])
        ) {
            $start = Carbon::parse($this->state['startDate']);
            $end = Carbon::parse($this->state['endDate']);

            if ($end->lessThanOrEqualTo($start)) {
                $this->addError('state.endDate', __('End date cannot be before or equal to the start date.'));
            } else {
                $this->resetErrorBag('state.endDate');
                $this->totalDaysLeave = $start->diffInDays($end);
            }
        }

        if ($prop === 'state.leaveType') {
            $this->showLeaveDescription = true;

            if ($this->isLeaveRequireProof()) {
                $this->disabledEndDate = true;
            } else {
                $this->disabledEndDate = false;
            }
        }

        if ($prop === 'state.startDate' && $this->state['startDate']) {
            if ($this->isLeaveRequireProof()) {
                $allottedDays = $this->leaveCategories
                    ->where('leave_category_id', $this->state['leaveType'])
                    ->first()
                    ->allotted_days;

                $start = Carbon::parse($this->state['startDate']);
                $end = $start->copy()->addDays($allottedDays);

                $this->state['endDate'] = $end->format('Y-m-d');
                $this->totalDaysLeave = $start->diffInDays($end);
            }
        }
    }

    private function isLeaveRequireProof(): bool
    {
        return $this->leaveCategories
            ->where('is_proof_required')
            ->contains($this->state['leaveType']);
    }
    
    public function rules()
    {
        return [
            'state.leaveType'   => 'required',
            'state.startDate'   => 'required|after:today|before:state.endDate',
            'state.endDate'     => 'required|after:state.startDate',
            'state.reason'      => 'required',
            'attachments'       => [function ($attribute, $value, $fail) {
                if ($this->isLeaveRequireProof() && empty($value)) {
                    $fail(__('Attachments are required for the selected leave type.'));
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            'state.leaveType.required'  => __('Please select your type of leave.'),
            'state.startDate.required'  => __('Please select the starting date of your leave.'),
            'state.endDate.required'    => __('Please select the ending date of your leave.'),
            'state.startDate.after'     => __('The start date must be after today.'),
            'state.startDate.before'    => __('The start date must be before the end date.'),
            'state.endDate.after'       => __('The end date must be after the start date.'),
            'state.reason.required'     => __('Write your reason for leave.'),
            'attachments.required'      => __('Attachments are required for the selected leave type.'),
        ];
    }

    public function save()
    {
        $this->authorize('fileLeaveRequest');

        $this->validate();

        DB::transaction(function () {
            $leave = EmployeeLeave::create([
                'employee_id' => $this->employee->employee_id,
                'leave_category_id' => $this->state['leaveType'],
                'reason' => $this->state['reason'],
                'start_date' => $this->state['startDate'],
                'end_date' => $this->state['endDate'],
            ]);

            $this->storeAttachments($leave);
        });
        
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('showSuccessToast', [
            'type' => 'success',
            'message' => __("Your file for leave request has been successfully submitted.")
        ]);
    }

    public function storeAttachments(EmployeeLeave $leave): void
    {
        Storage::disk('local')->makeDirectory(FilePath::LEAVES->value);

        $leaveAttachments = [];

        foreach ($this->attachments as $attachment) {

            $hashedVersion = sprintf('%s-%d', $attachment->hashName(), Auth::id());
            
            $attachment->storeAs(FilePath::LEAVES->value, $hashedVersion, 'local');

            array_push($leaveAttachments, [
                'emp_leave_id'      => $leave->emp_leave_id,
                'hashed_attachment' => $hashedVersion,
                'attachment_name'   => $attachment->getClientOriginalName(),
            ]); 
        }

        DB::table('employee_leave_attachments')->insert($leaveAttachments); 
    }

    public function removeAttachment(int $index)
    {
        unset($this->attachments[$index]);
    }

    #[Computed]
    public function leaveCategories()
    {
        return LeaveCategory::all()
            ->reject(function ($type) {
                if (Sex::from($this->employee->sex)->value === Sex::MALE->value) {
                    return in_array($type->leave_category_name, [
                        'Maternity Leave',
                        'Victims of Violence Against Women and Their Children (VAWC) Leave',
                        'Special Leave Benefits for Women',
                    ]);
                } else {
                    return $type->leave_category_name === 'Paternity Leave'; 
                }
        });
    }

    #[Computed]
    public function employee()
    {
        return Auth::user()->account;
    }

    #[Computed]
    public function vacationLeaveCredits()
    {
        return Auth::user()->account->silCredit->vacation_leave_credits;
    }

    #[Computed]
    public function sickLeaveCredits()
    {
        return Auth::user()->account->silCredit->sick_leave_credits;
    }

    public function render()
    {
        return view('livewire.employee.leaves.request-leave');
    }
}
