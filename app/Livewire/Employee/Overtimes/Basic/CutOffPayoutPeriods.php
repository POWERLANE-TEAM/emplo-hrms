<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class CutOffPayOutPeriods extends Component
{
    #[Locked]
    public $overtime;

    #[Locked] 
    public ?int $payroll; 

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
            'payrollApproval',
            // 'payrollApproval.payroll',
            // 'payrollApproval.initialApprover',
            // 'payrollApproval.secondaryApprover',
            // 'payrollApproval.thirdApprover',
        ])
            ->when($this->payroll, function ($query) {
                $query->whereHas('payrollApproval.payroll', function ($subquery) {
                    $subquery->where('payroll_id', $this->payroll);
                });          
            })
            ->get();
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
        return view('livewire.employee.overtimes.basic.cut-off-payout-periods');
    }
}
