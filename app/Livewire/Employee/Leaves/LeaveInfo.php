<?php

namespace App\Livewire\Employee\Leaves;

use App\Models\ServiceIncentiveLeaveCredit;
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

    #[Locked]
    public $routePrefix;

    public $feedback;

    private function renderDestructiveButtons()
    {
        if ($this->user->account->leaves->doesntContain($this->leave)) {
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
            } else {
                $this->reset('destructiveBtnsEnabled', 'approvingStage');
            }
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
                if (is_null($this->leave->category->allotted_days)) {
                    $sil = ServiceIncentiveLeaveCredit::find($this->leave->employee->employee_id);
                    
                    if ($this->leave->category->leave_category_name === 'Sick Leave') {
                        $sil->where('sick_leave_credits', '>', 0)
                            ->decrement('sick_leave_credits');
                    } elseif ($this->leave->category->leave_category_name === 'Vacation Leave') {
                        $sil->where('vacation_leave_credits', '>', 0)
                            ->decrement('vacation_leave_credits');
                    }
                }
            }
        });

        $this->dispatchEvents(
            'success', 
            __("{$this->leave->employee->last_name}'s {$this->leave->category->leave_category_name} request was approved successfully."));
    }

    public function denyLeaveRequest()
    {
        DB::transaction(function() {
            $this->leave->update([
                'denier' => $this->employeeId,
                'denied_at' => now(),
                'feedback' => $this->feedback,
            ]);
        });

        $this->dispatchEvents(
            'info', 
            __("{$this->leave->employee->last_name}'s {$this->leave->category->leave_category_name} request was denied."));
    }

    private function dispatchEvents(string $type, string $message)
    {
        $this->dispatch('leaveRequestApproved')->to(Approvals::class);
        $this->dispatch('leaveRequestApproved')->to(RequestorInfo::class);
        $this->dispatch('showSuccessToast', compact('message', 'type'));
        $this->dispatch('changesSaved')->self();

        $this->resetExcept('leave', 'routePrefix');
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

    public function rules(): array
    {
        return [
            'feedback' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'feedback' => __('Please provide the reason for denial'),
        ];
    }

    #[On('changesSaved')]
    public function render()
    {
        $this->renderDestructiveButtons();
        
        return view('livewire.employee.leaves.leave-info');
    }
}
