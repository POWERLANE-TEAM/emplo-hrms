<?php

namespace App\Livewire\Employee\Attendance;

use App\Models\Holiday;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\AttendanceLog;
use App\Models\EmployeeLeave;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Enums\BiometricPunchType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;

class Summary extends Component
{
    #[Locked]
    #[Reactive]
    public $period;

    #[Locked]
    public $totalOtHours;

    #[Locked]
    public $totalHours;

    #[Locked]
    public $totalPresentWorkingDays;

    #[Locked]
    public $totalAbsents;

    #[Locked]
    public $tardyDaysCount;

    #[Locked]
    public $documentLeavesCount;

    #[Locked]
    public $overtimeDaysCount;

    public Employee $employee;

    private function getTotalOtHours()
    {
        $overtimes = Overtime::where('employee_id', $this->employee->employee_id)
            ->whereHas('payrollApproval.payroll', function ($query) {
                $query->where('payroll_id', $this->period);
            })
            ->whereNotNull('authorizer_signed_at')
            ->get();

        $totalSecs = $overtimes->map(function ($ot) {
            $start = Carbon::parse($ot->start_time);
            $end = Carbon::parse($ot->end_time);

            return $start->diffInSeconds($end);
        })->sum();

        $hours      = floor($totalSecs / 3600);
        $minutes    = floor(($totalSecs % 3600) / 60);
        $seconds    = $totalSecs % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    public function getTotalHours()
    {
        $periodStart = Carbon::parse($this->payrollModel->cut_off_start);
        $periodEnd = Carbon::parse($this->payrollModel->cut_off_end);

        $attLogs = AttendanceLog::where('employee_id', $this->employee->employee_id)
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
    
        $hours      = floor($totalSecs / 3600);
        $minutes    = floor(($totalSecs % 3600) / 60);
        $seconds    = $totalSecs % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    #[Computed]
    public function payrollModel()
    {
        return Payroll::find($this->period);
    }

    private function getTotalPresentWorkingDays()
    {
        $periodStart = Carbon::parse($this->payrollModel->cut_off_start);
        $periodEnd = Carbon::parse($this->payrollModel->cut_off_end);
        
        $holidays = Holiday::whereBetween('date', [$periodStart, $periodEnd])
            ->pluck('date')->toArray();
    
        $attLogs = AttendanceLog::where('employee_id', $this->employee->employee_id)
            ->whereBetween('timestamp', [$periodStart, $periodEnd])
            ->where('type', BiometricPunchType::CHECK_IN->value)
            ->get()
            ->map(function ($log) {
                return Carbon::parse($log->timestamp)->toDateString();
            })
            ->toArray();
    
        $workingDays = collect();
    
        for ($date = $periodStart; $date->lte($periodEnd); $date->addDay()) {
            $dayOfWeek = $date->dayOfWeek;
            
            if ($dayOfWeek != Carbon::SATURDAY && $dayOfWeek != Carbon::SUNDAY && !in_array($date->toDateString(), $holidays)) {
                if (in_array($date->toDateString(), $attLogs)) {
                    $workingDays->push($date->toDateString());
                }
            }
        }
    
        return $workingDays->count();
    }
    
    public function getTotalAbsents()
    {
        $periodStart = Carbon::parse($this->payrollModel->cut_off_start);
        $periodEnd = Carbon::parse($this->payrollModel->cut_off_end);
    
        $allDays = collect();
        for ($date = $periodStart->copy(); $date->lte(min($periodEnd, today())); $date->addDay()) {
            $allDays->push($date->toDateString());
        }
    
        $attLogs = AttendanceLog::where('employee_id', $this->employee->employee_id)
            ->whereBetween('timestamp', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->get();
    
        $presentDays = collect($attLogs)
            ->map(fn ($log) => Carbon::parse($log->timestamp)->toDateString())
            ->toArray();

        $absentDays = $allDays->diff($presentDays)->filter(function ($date) {
            $dayOfWeek = Carbon::parse($date)->dayOfWeek;
            return $dayOfWeek != Carbon::SATURDAY && $dayOfWeek != Carbon::SUNDAY;
        });       

        $absentCount = $absentDays->count();
    
        return $absentCount;
    }    

    private function countTardyDays()
    {
        $periodStart = Carbon::parse($this->payrollModel->cut_off_start);
        $periodEnd = Carbon::parse($this->payrollModel->cut_off_end);

        $shift = $this->employee->shift;

        $attLogs = AttendanceLog::where('employee_id', $this->employee->employee_id)
            ->whereBetween('timestamp', [$periodStart, $periodEnd])
            ->where('type', BiometricPunchType::CHECK_IN->value)
            ->get();

        $tardyDays = $attLogs->filter(function ($log) use ($shift) {
            $checkInTime = Carbon::parse($log->timestamp);
            $shiftStartTime = Carbon::parse($shift->start_time);
            return $checkInTime->gt($shiftStartTime);
        });

        return $tardyDays->count();
    }

    private function countDocumentedLeaves()
    {
        $periodStart = Carbon::parse($this->payrollModel->cut_off_start);
        $periodEnd = Carbon::parse($this->payrollModel->cut_off_end);

        $leaves = EmployeeLeave::where('employee_id', $this->employee->employee_id)
            ->whereBetween('start_date', [$periodStart, $periodEnd])
            ->whereNotNull('fourth_approver_signed_at')
            ->get();

        return $leaves->count();
    }

    private function countOvertimeDays()
    {
        $overtimes = Overtime::where('employee_id', $this->employee->employee_id)
            ->whereHas('payrollApproval.payroll', function ($query) {
                $query->where('payroll_id', $this->period);
            })
            ->whereNotNull('authorizer_signed_at')
            ->get();

        $overtimeDays = $overtimes->map(function ($ot) {
            return Carbon::parse($ot->start_time)->toDateString();
        })->unique();

        return $overtimeDays->count();
    }

    public function render()
    {
        $this->totalOtHours = $this->getTotalOtHours();
        $this->totalHours = $this->getTotalHours();
        $this->totalPresentWorkingDays = $this->getTotalPresentWorkingDays();
        $this->totalAbsents = $this->getTotalAbsents();
        $this->tardyDaysCount = $this->countTardyDays();
        $this->documentLeavesCount = $this->countDocumentedLeaves();
        $this->overtimeDaysCount = $this->countOvertimeDays();

        return view('livewire.employee.attendance.summary');
    }
}
