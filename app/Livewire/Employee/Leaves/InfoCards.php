<?php

namespace App\Livewire\Employee\Leaves;

use App\Models\EmployeeLeave;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class InfoCards extends Component
{
    #[Locked]
    public $deniedLeaveRequests;

    #[Locked]
    public $leaveBalance;

    #[Locked]
    public $daysTaken;

    #[Locked]
    public $awaitingFinalApprovals;

    public function mount()
    {
        $this->deniedLeaveRequests = $this->leaves
            ->whereNotNull('denied_at')
            ->count();

        $this->leaveBalance = Auth::user()->account->jobDetail->leave_balance;

        $this->daysTaken = $this->leaves
            ->whereNotNull('fourth_approver_signed_at')
            ->count();

        $this->awaitingFinalApprovals = $this->leaves
            ->whereNotNull('denied_at')
            ->whereNotNull('third_approver_signed_at')
            ->whereNull('fourth_approver_signed_at')
            ->count();
    }

    #[Computed]
    public function leaves()
    {
        return Auth::user()->account->leaves;
    }

    public function render()
    {
        return view('livewire.employee.leaves.info-cards');
    }
}
