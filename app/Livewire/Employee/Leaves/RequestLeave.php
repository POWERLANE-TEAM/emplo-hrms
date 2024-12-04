<?php

namespace App\Livewire\Employee\Leaves;

use App\Models\EmployeeLeave;
use Livewire\Component;
use App\Enums\UserPermission;
use App\Models\LeaveCategory;
use Illuminate\Support\Carbon;
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

    public $totalDaysLeave;

    public function updated($prop)
    {
        if (
            in_array($prop, ['state.startDate', 'state.endDate']) &&
            isset($this->state['startDate'], $this->state['endDate'])
        ) {
            $start = Carbon::parse($this->state['startDate']);
            $end = Carbon::parse($this->state['endDate']);

            if ($end->lt($start)) {
                $this->addError('state.endDate', __('End date cannot be before the start date.'));
            } else {
                $this->totalDaysLeave = $start->diffInDays($end);
            }
        }
    }
    
    public function rules()
    {
        return [
            'state.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'state.leaveType' => __('Please choose a leave type.'),
            'state.startDate' => __('Select a starting date.'),
            'state.endDate' => __('Select an end date.'),
            'state.reason' => __('Write your reason for leave.'),
        ];
    }

    public function save()
    {
        $this->validate();

        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_LEAVE_REQUEST)) {
            $this->reset();

            abort (403);
        }

        DB::transaction(function () {
            $leave = EmployeeLeave::create([
                'employee_id' => Auth::id(),
                'leave_id' => $this->state['leaveType'],
                'reason' => $this->state['reason'],
                'start_date' => $this->state['startDate'],
                'end_date' => $this->state['endDate'],
            ]);

            $leave->processes()->create([
                'processable_type' => 'employee_leave',
                'processable_id' => $leave->emp_leave_id,
            ]);
        });
        
        $this->reset();
        $this->resetErrorBag();
    }

    #[Computed]
    public function leaveCategories()
    {
        return LeaveCategory::all()->pluck('leave_name', 'leave_id')->toArray();
    }
    
    public function render()
    {
        return view('livewire.employee.leaves.request-leave');
    }
}
