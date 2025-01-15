<?php

namespace App\Livewire\Employee\Dashboard;

use App\Enums\Payroll;
use Livewire\Component;
use App\Models\Overtime;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Enums\BiometricPunchType;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll as PayrollModel;

class InfoCards extends Component
{
    #[Locked]
    public $workedHoursCurrent;

    #[Locked]
    public $workedHoursPrevious;

    #[Locked]
    public $overtimeHours;

    #[Locked]
    public $nextPayout;

    public function mount()
    {
        $this->workedHoursCurrent = $this->getCurrentTotalHours();

        $this->workedHoursPrevious = $this->getPreviousTotalHours();

        $this->overtimeHours = $this->getTotalOtHours();

        $this->nextPayout = Payroll::getPayoutDate(now(), isReadableFormat: true);
    }

    private function getTotalOtHours()
    {
        $overtimes = Overtime::where('employee_id', Auth::user()->account->employee_id)
            ->whereHas('payrollApproval.payroll', function ($query) {
                $query->where('payroll_id', $this->latestPeriod->payroll_id);
            })
            ->whereNotNull('authorizer_signed_at')
            ->get();

        $totalSecs = $overtimes->map(function ($ot) {
            $start = Carbon::parse($ot->start_time);
            $end = Carbon::parse($ot->end_time);

            return $start->diffInSeconds($end);
        })->sum();

        return floor($totalSecs / 3600);
    }

    private function getPreviousTotalHours()
    {
        $period = PayrollModel::latest('cut_off_start')->skip(1)->first();

        $periodStart = Carbon::parse($period->cut_off_start);
        $periodEnd = Carbon::parse($period->cut_off_end);

        $attLogs = AttendanceLog::where('employee_id', Auth::user()->account->employee_id)
            ->whereBetween('timestamp', [$periodStart, $periodEnd])
            ->orderBy('timestamp')
            ->get()
            ->groupBy(function ($log) {
                return Carbon::parse($log->timestamp)->toDateString();
            });

        $totalSecs = 0;

        foreach ($attLogs as $date => $logs) {
            $checkIn = null;
            $checkOut = null;
    
            foreach ($logs as $log) {
                if ($log->type == BiometricPunchType::CHECK_IN->value) {
                    $checkIn = Carbon::parse($log->timestamp);
                } elseif ($log->type == BiometricPunchType::CHECK_OUT->value) {
                    $checkOut = Carbon::parse($log->timestamp);
                }
    
                if ($checkIn && $checkOut) {
                    $totalSecs += $checkIn->diffInSeconds($checkOut);
                    $checkIn = null;
                    $checkOut = null;
                }
            }
        }
    
        return floor($totalSecs / 3600);
    }

    private function getCurrentTotalHours()
    {
        $periodStart = Carbon::parse($this->latestPeriod->cut_off_start);
        $periodEnd = Carbon::parse($this->latestPeriod->cut_off_end);

        $attLogs = AttendanceLog::where('employee_id', Auth::user()->account->employee_id)
            ->whereBetween('timestamp', [$periodStart, $periodEnd])
            ->orderBy('timestamp')
            ->get()
            ->groupBy(function ($log) {
                return Carbon::parse($log->timestamp)->toDateString();
            });

        $totalSecs = 0;

        foreach ($attLogs as $date => $logs) {
            $checkIn = null;
            $checkOut = null;
    
            foreach ($logs as $log) {
                if ($log->type == BiometricPunchType::CHECK_IN->value) {
                    $checkIn = Carbon::parse($log->timestamp);
                } elseif ($log->type == BiometricPunchType::CHECK_OUT->value) {
                    $checkOut = Carbon::parse($log->timestamp);
                }
    
                if ($checkIn && $checkOut) {
                    $totalSecs += $checkIn->diffInSeconds($checkOut);
                    $checkIn = null;
                    $checkOut = null;
                }
            }
        }
    
        return floor($totalSecs / 3600);
    }

    #[Computed]
    public function latestPeriod()
    {
        return PayrollModel::latest('cut_off_start')->first();
    }

    public function render()
    {
        return view('livewire.employee.dashboard.info-cards');
    }
}
