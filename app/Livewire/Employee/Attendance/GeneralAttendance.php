<?php

namespace App\Livewire\Employee\Attendance;

use App\Enums\BiometricPunchType;
use App\Models\AttendanceLog;
use App\Models\EmployeeLeave;
use App\Models\Holiday;
use App\Models\Overtime;
use App\Models\Payroll;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class GeneralAttendance extends Component
{
    public $selectedView = 'summary';

    public $period;

    public function mount()
    {
        $this->period = $this->periods->first()->payroll_id;
    }

    #[Computed]
    public function attendanceLogs()
    {
        return AttendanceLog::where('employee_id', $this->userEmployee->employee_id)
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
        return EmployeeLeave::where('employee_id', $this->userEmployee->employee_id)
            ->whereNotNull('fourth_approver_signed_at')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->emp_leave_id => [$item->start_date, $item->end_date]])
            ->toArray();
    }

    #[Computed]
    public function overtimes()
    {
        return Overtime::where('employee_id', $this->userEmployee->employee_id)
            ->whereNotNull('authorizer_signed_at')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->overtime_id => $item->date])
            ->toArray();
    }

    #[Computed]
    public function userEmployee()
    {
        return Auth::user()->account;
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
        // dd(
        //     $this->attendanceLogs,
        //     $this->overtimes,
        // );

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
                'start' => $timestamp->toDateString(),
                'classNames' => ['bg-primary'],
            ];
        }

        foreach ($this->holidays as $holiday) {
            [$title, $date] = $holiday;
            $events[] = [
                'title' => $title,
                'start' => Carbon::createFromFormat('m-d', $date)->setYear(now()->year)->toDateString(),
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
                if (! $date->isWeekend()) {
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

        // dd($events);

        return view('livewire.employee.attendance.general-attendance', compact('events'));
    }
}
