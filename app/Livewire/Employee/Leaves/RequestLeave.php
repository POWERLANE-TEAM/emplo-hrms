<?php

namespace App\Livewire\Employee\Leaves;

use Livewire\Component;
use App\Models\EmployeeLeave;
use App\Models\LeaveCategory;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestLeave extends Component
{
    #[Validate]
    public $state = [
        'leaveType' => null,
        'startDate' => null,
        'endDate' => null,
        'reason' => '',
    ];

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
    }
    
    public function rules()
    {
        return [
            'state.leaveType'   => 'required',
            'state.startDate'   => 'required|after:today|before:state.endDate',
            'state.endDate'     => 'required|after:state.startDate',
            'state.reason'      => 'required',
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
        ];
    }

    public function save()
    {
        $this->authorize('fileLeaveRequest');

        $this->validate();

        DB::transaction(function () {
            EmployeeLeave::create([
                'employee_id' => $this->employee->employee_id,
                'leave_category_id' => $this->state['leaveType'],
                'reason' => $this->state['reason'],
                'start_date' => $this->state['startDate'],
                'end_date' => $this->state['endDate'],
            ]);
        });
        
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('showSuccessToast', [
            'type' => 'success',
            'message' => __("Your file for leave request has been successfully submitted.")
        ]);
    }

    #[Computed]
    public function leaveCategories()
    {
        return LeaveCategory::all()
            ->pluck('leave_category_name', 'leave_category_id')
            ->toArray();
    }

    #[Computed]
    public function employee()
    {
        return Auth::user()->account;
    }
    
    public function render()
    {
        return view('livewire.employee.leaves.request-leave');
    }
}
