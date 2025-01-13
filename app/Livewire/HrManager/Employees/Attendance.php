<?php

namespace App\Livewire\HrManager\Employees;

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

class Attendance extends Component
{
    public $period;

    public Employee $employee;

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

    public function mount()
    {
        $this->period = $this->periods->first()->payroll_id;
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

    #[Computed]
    public function payrollModel()
    {
        return Payroll::find($this->period);
    }

    #[Computed]
    public function attendanceLogs()
    {
        return AttendanceLog::where('employee_id', $this->employee->employee_id)
            ->get()
            ->filter(fn ($item) => $item->type === BiometricPunchType::CHECK_IN->value)
            ->mapWithKeys(fn ($item) => [$item->uid => [$item->type, $item->timestamp]])
            ->toArray();
    }

    #[Computed]
    public function periods()
    {
        return Payroll::latest('cut_off_start')->get();
    }

    #[Computed]
    public function leaves()
    {
        return EmployeeLeave::where('employee_id', $this->employee->employee_id)
            ->whereNotNull('fourth_approver_signed_at')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->emp_leave_id => [$item->start_date, $item->end_date]])
            ->toArray();
    }

    #[Computed]
    public function overtimes()
    {
        return Overtime::where('employee_id', $this->employee->employee_id)
            ->whereNotNull('authorizer_signed_at')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->overtime_id => $item->date])
            ->toArray();
    }

    #[Computed]
    public function holidays()
    {
        return Holiday::all()->mapWithKeys(function ($item) {
            return [$item->id => [$item->event, $item->date]];
        })->toArray();
    }

    public function render()
    {
        $merge = array_merge($this->overtimes, $this->leaves);
    
        $events = [];
        
        foreach ($merge as $key => $dates) {
            if (is_array($dates)) {
                $events[] = [
                    'title' => 'Leave',
                    'start' => Carbon::parse($dates[0])->toDateString(),
                    'end' => Carbon::parse($dates[1])->toDateString(),
                    'classNames' => ['bg-teal'],
                ];
            } else {
                $events[] = [
                    'title' => 'Overtime',
                    'start' => Carbon::parse($dates)->toDateString(),
                    'classNames' => ['bg-info'],
                ];
            }
        }

        foreach ($this->attendanceLogs as $uid => [$type, $timestamp]) {
            $events[] = [
                'title' => 'Worked Regular',
                'start' => Carbon::parse($timestamp)->toDateString(),
                'classNames' => ['bg-primary'],
            ];
        }

        foreach ($this->holidays as $holiday) {
            [$title, $date] = $holiday;
            $events[] = [
                'title' => $title,
                'start' => Carbon::parse($date)->toDateString(),
                'classNames' => ['bg-warning-emphasis'],
            ];
        }

        $startDate = Carbon::parse(Payroll::orderBy('cut_off_start', 'asc')->first()->cut_off_start);
        $endDate = now();
    
        $allDays = collect();
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }
            $allDays->push($date->toDateString());
        }
    
        $presentDays = collect($events)->pluck('start')->toArray();
        $leaveDays = collect($this->leaves)->flatMap(function ($dates) {
            $leaveRange = collect();
            $start = Carbon::parse($dates[0]);
            $end = Carbon::parse($dates[1]);
        
            for ($date = $start; $date->lte($end); $date->addDay()) {
                if (!$date->isWeekend()) {
                    $leaveRange->push($date->toDateString());
                }
            }
        
            return $leaveRange;
        })->toArray();

        $excludedDays = array_merge($presentDays, $leaveDays);
        $absentDays = $allDays->diff($excludedDays);
        
        foreach ($absentDays as $absentDay) {
            $events[] = [
                'title' => 'Absent',
                'start' => $absentDay,
                'classNames' => ['bg-danger'],
            ];
        }

        $this->totalPresentWorkingDays = $this->getTotalPresentWorkingDays();
        $this->totalAbsents = $this->getTotalAbsents();
        $this->tardyDaysCount = $this->countTardyDays();
        $this->documentLeavesCount = $this->countDocumentedLeaves();
        $this->overtimeDaysCount = $this->countOvertimeDays();

        return view('livewire.hr-manager.employees.attendance', compact('events'));
    }
}
