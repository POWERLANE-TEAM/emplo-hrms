<?php

namespace App\Livewire\Employee\Leaves;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\EmployeeLeave;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveInfo extends Component
{
    public EmployeeLeave $leave;
    
    #[Locked]
    public $destructiveBtnsEnabled = false;

    #[Locked]
    public $approvingStage;

    public function mount()
    {
        $this->leave->load([
            'category',
        ]);
    }

    private function renderDestructiveButtons()
    {
        if (is_null($this->leave->fourth_approver_signed_at) && 
            $this->leave->third_approver_signed_at &&
            $this->user->can('approveLeaveRequestFinal')
        ) {
            $this->destructiveBtnsEnabled = true;
            $this->approvingStage = 'fourth';
        } elseif (is_null($this->leave->third_approver_signed_at) &&
            $this->leave->secondary_approver_signed_at &&
            $this->user->can('approveAnyLeaveRequest')
        ) {
            $this->destructiveBtnsEnabled = true;
            $this->approvingStage = 'third';
        } elseif (is_null($this->leave->secondary_approver_signed_at) &&
            $this->leave->initial_approver_signed_at &&
            $this->user->can('approveSubordinateLeaveRequest')
        ) {
            $this->destructiveBtnsEnabled = true;
            $this->approvingStage = 'secondary';
        } elseif (is_null($this->leave->initial_approver_signed_at) &&
            $this->user->can('approveSubordinateLeaveRequest')
        ) {
            $this->destructiveBtnsEnabled = true;
            $this->approvingStage = 'initial';
        }
    }

    public function approveLeaveRequest()
    {
        DB::transaction(function() {
            $this->leave->update([
                "{$this->approvingStage}_approver" => $this->employeeId,
                "{$this->approvingStage}_approver_signed_at" => now(),
            ]);

            if ($this->approvingStage === 'fourth') {
                $this->leave->employee->jobDetail()->decrement('leave_balance');
            }
        });

        $this->dispatch('leaveRequestApproved')->to(Approvals::class);
    }

    public function denyLeaveRequest()
    {
        $this->authorize('approveSubordinateLeaveRequest');

        DB::transaction(function() {
            $this->leave->update([
                'denier' => $this->employeeId,
                'denied_at' => now(),
            ]);
        });
    }

    #[Computed]
    public function employeeId()
    {
        return Auth::user()->account->employee_id;
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    public function render()
    {
        $this->renderDestructiveButtons();
        
        return view('livewire.employee.leaves.leave-info');
    }
}
