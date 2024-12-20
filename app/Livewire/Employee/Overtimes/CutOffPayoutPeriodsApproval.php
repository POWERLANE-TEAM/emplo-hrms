<?php

namespace App\Livewire\Employee\Overtimes;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class CutOffPayoutPeriodsApproval extends Component
{
    public ?Employee $employee;

    #[Locked]
    public $overtime;

    #[Locked] 
    public int $payroll; 

    #[Locked]
    public $totalOtHours = 0;

    #[Locked]
    public $status;

    #[Locked]
    public $otSummary;

    #[Locked]
    public $remainingApprovers = [];

    public function mount()
    {
        $this->persistComponent();
    }

    #[On('payrollDateModified')]
    public function updateOtInfo($value)
    {
        $this->payroll = $value;

        $this->persistComponent();
    }

    public function persistComponent()
    {
        $this->getOtInfo();
        $this->getTotalOtHours();
        $this->getOtSummaryInfo();
        $this->getOtSummaryStatus();
        $this->getRemainingApprovers();
    }

    private function getOtInfo()
    {
        $this->overtime = Overtime::with([
            'payrollApproval.payroll',
            'payrollApproval.initialApprover',
            'payrollApproval.secondaryApprover',
            'payrollApproval.thirdApprover',
        ])
            ->whereHas('payrollApproval.payroll', function ($query) {
                $query->where('payroll_id', $this->payroll);
            })
            ->when($this->employee, function ($query) {
                return $query->where('employee_id', $this->employee->employee_id);
            })
            ->get();
    }

    #[On('otSummaryApprovedInitial')]
    #[On('otSummaryApprovedSecondary')]
    #[On('otSummaryApprovedTertiary')]
    public function updateComponent()
    {
        $this->getRemainingApprovers();
        $this->getOtSummaryInfo();
        $this->getOtSummaryStatus();
    }


    private function getRemainingApprovers()
    {
        if (is_null($this->overtime->first()->payrollApproval->initial_approver_signed_at)) {
            $this->remainingApprovers[] = 'initial';
        }

        if (is_null($this->overtime->first()->payrollApproval->secondary_approver_signed_at)) {
            $this->remainingApprovers[] = 'secondary';
        }

        if (is_null($this->overtime->first()->payrollApproval->third_approver_signed_at)) {
            $this->remainingApprovers[] = 'tertiary';
        }
    }

    
    private function getTotalOtHours()
    {
        $totalSecs = $this->overtime->map(function ($ot) {
            $start = Carbon::parse($ot->start_time);
            $end = Carbon::parse($ot->end_time);

            return $start->diffInSeconds($end);
        })->sum();

        $hours                  = floor($totalSecs / 3600);
        $minutes                = floor(($totalSecs % 3600) / 60);
        $seconds                = $totalSecs % 60;
        $this->totalOtHours     = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    private function getOtSummaryInfo()
    {   
        $payrollApproval = $this->overtime?->first()?->payrollApproval;

        $this->otSummary = (object) [
            'initialApprover'       => $this->checkIfSameAsAuthUser($payrollApproval?->initialApprover),
            'secondApprover'        => $this->checkIfSameAsAuthUser($payrollApproval?->secondaryApprover),
            'thirdApprover'         => $this->checkIfSameAsAuthUser($payrollApproval?->thirdApprover),
            'initialApprovalDate'   => $payrollApproval?->initial_approver_signed_at,
            'secondApprovalDate'    => $payrollApproval?->secondary_approver_signed_at,
            'thirdApprovalDate'     => $payrollApproval?->third_approver_signed_at,
        ];       
    }

    private function checkIfSameAsAuthUser(?Employee $employee)
    {
        return $employee?->employee_id === $this->authEmployee->employee_id
            ? "{$employee?->full_name} (You)"
            : $employee?->full_name;
    }

    private function getOtSummaryStatus()
    {
        $this->status = $this->overtime->first()->payrollApproval->third_approver_signed_at
            ? __('Approved')
            : __('Pending');
    }    

    public function approveOtSummaryInitial()
    {    
        if (is_null($this->overtime->first()->payrollApproval->initial_approver_signed_at)) {
            $this->authorize('approveOvertimeSummaryInitial');

            $this->overtime->first()->payrollApproval()->update([
                'initial_approver_signed_at'  => now(),
                'initial_approver'            => $this->authEmployee->employee_id, 
            ]);

            $this->dispatch('otSummaryApprovedInitial')->self();
        }
    }

    public function approveOtSummarySecondary()
    {
        if (
            $this->overtime->first()->payrollApproval->initial_approver_signed_at &&
            is_null($this->overtime->first()->payrollApproval->secondary_approver_signed_at)
        ) {
            $this->authorize('approveOvertimeSummarySecondary');

            $this->overtime->first()->payrollApproval()->update([
                'secondary_approver_signed_at'  => now(),
                'secondary_approver'            => $this->authEmployee->employee_id, 
            ]);

            $this->dispatch('otSummaryApprovedSecondary')->self();
        }
    }

    public function approveOtSummaryTertiary()
    {
        if (
            $this->overtime->first()->payrollApproval->initial_approver_signed_at &&
            $this->overtime->first()->payrollApproval->secondary_approver_signed_at &&
            is_null($this->overtime->first()->payrollApproval->third_approver_signed_at)
        ) {
            $this->authorize('approveOvertimeSummaryTertiary');

            $this->overtime->first()->payrollApproval()->update([
                'third_approver_signed_at'  => now(),
                'third_approver'            => $this->authEmployee->employee_id, 
            ]);

            $this->dispatch('otSummaryApprovedTertiary')->self();
        }
    }

    #[Computed]
    private function authEmployee()
    {
        return Auth::user()->account;
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function render()
    {
        return view('livewire.employee.overtimes.cut-off-payout-periods-approval');
    }
}
