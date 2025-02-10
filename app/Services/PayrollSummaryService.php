<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\Payroll;
use App\Models\Employee;
use App\Traits\HolidayUtils;
use App\Traits\OvertimeUtils;
use Illuminate\Support\Carbon;
use App\Traits\AttendanceUtils;
use App\Enums\BiometricPunchType;
use Illuminate\Database\Eloquent\Collection;

class PayrollSummaryService
{
    use AttendanceUtils;
    use OvertimeUtils;
    use HolidayUtils;

    public $latestPayroll;

    public $regularShift;

    public $nightDifferentialShift;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->latestPayroll = Payroll::latest('cut_off_end')->first();

        $this->regularShift = Shift::regular()->first();
        $this->nightDifferentialShift = Shift::nightDifferential()->first();

        if ($this->latestPayroll) {
            $this->regularHolidays = $this->getRegularHolidays($this->latestPayroll->cut_off_end);
            $this->specialHolidays = $this->getSpecialHolidays($this->latestPayroll->cut_off_end);
        }
    }   

    /**
     * Total Regular Shift Hours
     * 
     * Sum the differences of each check-in and out under regular shift's start and end time.
     *
     * @param Collection $attendanceLogs.
     * @return float|null
     */
    public function getTotalRegHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateRegularLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return 
                $this->isNotHoliday($log->timestamp) &&
                $log->timestamp->isWeekday();
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Night Differential Shift Hours
     * 
     * Sum the differences in hours of each check-in and out under night differential shift's start
     * and end time.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRegNightDiffHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateNightDiffLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return 
                $this->isNotHoliday($log->timestamp) &&
                $log->timestamp->isWeekday();
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Overtime Hours
     * 
     * Sum the differences in hours of overtime starting and end time, those of which
     * starting time is under regular shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRegOtHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return
                $this->isNotHoliday($ot->start_time) &&
                $ot->start_time->isWeekday() &&
                $ot->start_time->gte($this->regularShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalHrsOvertimes = $this->sumOvertimes($validatedOvertimes);

        return round($totalHrsOvertimes, 2);
    }

    /**
     * Total Regular Night Differential Overtime Hours
     * 
     * Sum the differences in hours of each overtime start and end time, those of which start time
     * is under night differential hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRegOtNightDiffHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return 
                $this->isNotHoliday($ot->start_time) &&
                $ot->start_time->isWeekday() &&
                $ot->start_time->gte($this->nightDifferentialShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalOvertimesHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimesHrs, 2);
    }

    /**
     * Total Regular Rest Day Hours
     * 
     * Sum the differences of each check-in and out under rest days (weekends) and regular shift
     * hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRestHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateRegularLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return 
                $this->isNotHoliday($log->timestamp) &&
                $log->timestamp->isWeekend();
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Night Differential Rest Day Hours
     * 
     * Sum the differences of each check-in and out under rest days (weekend) and night
     * differential shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRestNightDiffHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateNightDiffLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return 
                $this->isNotHoliday($log->timestamp) &&
                $log->timestamp->isWeekend();
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Rest Day Overtime Hours
     * 
     * Sum the differences of each overtime start and end time, those of which under rest 
     * days(weekends) and regular shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRestOtHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes =  $overtimes->filter(function ($ot) {
            return 
                $this->isNotHoliday($ot->start_time) &&
                $ot->start_time->isWeekend() &&
                $ot->start_time->gte($this->regularShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;
        
        $totalOvertimesHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimesHrs, 2);
    }

    /**
     * Total Night Differential Rest Day Overtime Hours
     * 
     * Sum the difference of each overtime check-in and out, those of which under rest
     * days(weekends) and night differential shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRestOtNightDiffHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return 
                $this->isNotHoliday($ot->start_time) &&
                $ot->start_time->isWeekend() &&
                $ot->start_time->gte($this->nightDifferentialShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalOvertimesHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimesHrs, 2);
    }

    /**
     * Total Regular Shift Regular Holiday Hours
     * 
     * Sum the differences of each check-in and out, those of which under regular holidays and
     * regular shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRegHolHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateRegularLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return
                $log->timestamp->isWeekday() && 
                $this->isRegularHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Night Differential Regular Holiday Hours
     * 
     * Sum the differences of each check-in and out, those of which under regular holidays and
     * night differential shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRegHolNightDiffHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateNightDiffLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return 
                $log->timestamp->isWeekday() &&
                $this->isRegularHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Shift Regular Holiday Overtime Hours
     * 
     * Sum the differences of each overtime start and end time, those of which under regular
     * holidays and regular shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRegHolOtHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvetimes = $overtimes->filter(function ($ot) {
            return 
                $ot->start_time->isWeekday() &&
                $this->isRegularHoliday($ot->start_time) &&
                $ot->start_time->gte($this->regularShift->start_time);
        });

        if ($validatedOvetimes->isEmpty()) return;

        $totalOvertimesHrs = $this->sumOvertimes($validatedOvetimes);

        return round($totalOvertimesHrs, 2);
    }

    /**
     * Total Night Differential Regular Holiday Overtime Hours
     * 
     * Sum the differences of each overtime start and end time, those of which under regular
     * holidays and night differential shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRegHolOtNightDiffHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return 
                $ot->start_time->isWeekday() &&
                $this->isRegularHoliday($ot->start_time) &&
                $ot->start_time->gte($this->nightDifferentialShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalOvertimesHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimesHrs, 2);
    }

    /**
     * Total Regular Shift Regular Holiday Rest Day Hours
     * 
     * Sum the difference of each check-in and out, those of which under regular holidays and rest
     * days(weekends), and regular shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRegHolRestHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateRegularLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return
                $log->timestamp->isWeekend() && 
                $this->isRegularHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Night Differential Regular Holiday Rest Day Hours
     * 
     * Sum the difference of each check-in and out, those of which under regular holidays and rest
     * days, and night differential shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalRegHolRestNightDiffHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateNightDiffLogs($attendanceLogs);
        
        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return
                $log->timestamp->isWeekend() && 
                $this->isRegularHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Shift Regular Holiday Rest Day Overtime Hours
     * 
     * Sum the difference of each overtime's start and end time, those of which under regular
     * holiday and rest days(weekends), and regular shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRegHolRestOtHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return 
                $this->isRegularHoliday($ot->start_time) &&
                $ot->start_time->isWeekend() &&
                $ot->start_time->gte($this->regularShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalHrsOvertimes = $this->sumOvertimes($validatedOvertimes);

        return round($totalHrsOvertimes, 2);
    }

    /**
     * Total Night Differential Regular Holiday Rest Day Overtime Hours
     * 
     * Sum the difference of each overtime's start and end time, those of which under regular
     * holiday and rest days(weekends), and night differential shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalRegHolRestOtNightDiffHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return
                $this->isRegularHoliday($ot->start_time) &&
                $ot->start_time->isWeekend() &&
                $ot->start_time->gte($this->nightDifferentialShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalHrsOvertimes = $this->sumOvertimes($validatedOvertimes);

        return round($totalHrsOvertimes, 2);
    }

    /**
     * Total Regular Shift Special Holiday Hours
     * 
     * Sum the difference of each check-in and out, those of which under special holidays and
     * regular shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalSpeHolHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateRegularLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return
                $log->timestamp->isWeekday() && 
                $this->isSpecialHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Night Differential Special Holiday Hours
     * 
     * Sum the difference of each check-in and out, those of which under special holidays and
     * night differential shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalSpeHolNightDiffHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateNightDiffLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return
                $log->timestamp->isWeekday() && 
                $this->isSpecialHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Shift Special Holiday Overtime Hours
     * 
     * Sum the differences of each overtime's start and end time, those of which under special
     * holiday and regular shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalSpeHolOtHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return
                $ot->start_time->isWeekday() &&
                $this->isSpecialHoliday($ot->start_time) &&
                $ot->start_time->gte($this->regularShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalOvertimeHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimeHrs, 2);
    }

    /**
     * Total Night Differential Special Holiday Overtime Hours
     * 
     * Sum the differences of each overtime's start and end time, those of which under special
     * holiday and night differential shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalSpeHolOtNightDiffHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return
                $ot->start_time->isWeekday() &&
                $this->isSpecialHoliday($ot->start_time) &&
                $ot->start_time->gte($this->nightDifferentialShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalOvertimeHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimeHrs, 2);
    }

    /**
     * Total Regular Shift Special Holiday Rest Day Hours
     * 
     * Sum the differences of each check-in and out, those of which under special holiday and rest
     * day(weekends), and within regular shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalSpeHolRestHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateRegularLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return 
                $log->timestamp->isWeekend() &&
                $this->isSpecialHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Night Differential Special Holiday Rest Day Hours
     * 
     * Sum the differences of each check-in and out, those of which under special holiday and rest
     * day(weekends), and within night differential shift hours.
     * 
     * @param Collection $attendanceLogs
     * @return float|null
     */
    public function getTotalSpeHolRestNightDiffHrs(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $validatedLogs = $this->validateNightDiffLogs($attendanceLogs);

        if ($validatedLogs->isEmpty()) return;

        $validatedLogs = $validatedLogs->filter(function ($log) {
            return
                $log->timestamp->isWeekend() && 
                $this->isSpecialHoliday($log->timestamp);
        })->groupBy('employee_id');

        if ($validatedLogs->isEmpty()) return;

        return $this->sumDtrLogs($validatedLogs);
    }

    /**
     * Total Regular Shift Special Holiday Rest Day Overtime Hours
     * 
     * Sum difference of each overtime's start and end time, those of which under special holiday
     * and rest day(weekends), and within regular shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalSpeHolRestOtHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return 
                $this->isSpecialHoliday($ot->start_time) &&
                $ot->start_time->isWeekend() &&
                $ot->start_time->gte($this->regularShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalHrsOvertimes = $this->sumOvertimes($validatedOvertimes);

        return round($totalHrsOvertimes, 2);
    }

    /**
     * Total Night Differential Special Holiday Rest Day Overtime Hours
     * 
     * Sum difference of each overtime's start and end time, those of which under special holiday
     * and rest day(weekends), and within night differential shift hours.
     * 
     * @param Collection $overtimes
     * @return float|null
     */
    public function getTotalSpeHolRestOtNightDiffHrs(Collection $overtimes)
    {
        if ($overtimes->isEmpty()) return;

        $validatedOvertimes = $overtimes->filter(function ($ot) {
            return
                $this->isSpecialHoliday($ot->start_time) &&
                $ot->start_time->isWeekend() &&
                $ot->start_time->gte($this->nightDifferentialShift->start_time);
        });

        if ($validatedOvertimes->isEmpty()) return;

        $totalOvertimeHrs = $this->sumOvertimes($validatedOvertimes);

        return round($totalOvertimeHrs, 2);
    }

    /**
     * Total Absent Days
     * 
     * Sum the difference between total working days and check-in days.
     * 
     * @param Collection $attendanceLogs
     * @return int|null
     */
    public function getTotalAbsentDays(Collection $attendanceLogs)
    {
        if ($attendanceLogs->isEmpty()) return;

        $start = $this->latestPayroll->cut_off_start;
        $end = $this->latestPayroll->cut_off_end;

        $totalPayrollDays = $start->diffInDays($end);

        $totalWorkingDays = 0;

        for ($i = $totalPayrollDays; $i > 0; $i--) {
            if (! $this->isHoliday($start) && $start->isWeekday()) {
                $totalWorkingDays++;
            }
            $start->addDay();
        }

        $totalValidatedLogs = $attendanceLogs->filter(function ($log) {
            return 
                $log->type === BiometricPunchType::CHECK_IN->value &&
                $log->timestamp->isBetween(
                    $this->latestPayroll->cut_off_start->startOfDay(),
                    $this->latestPayroll->cut_off_end->endOfDay()
                );
        })->count();

        return $totalWorkingDays - $totalValidatedLogs;
    }

    /**
     * Total Undertime Hours
     * 
     * Sum the difference in hours of each employee's check-out against his assigned end time of
     * shift schedule.
     * 
     * @param Employee $employee
     * @return float|null
     */
    public function getTotalUtimeHrs(Employee $employee)
    {
        $shiftEnd = Carbon::createFromFormat('H:i:s', $employee->shift->end_time);

        $validatedAttendanceLogs = $employee->attendanceLogs->filter(function ($log) {
            return 
                $log->type === BiometricPunchType::CHECK_OUT->value &&
                $log->timestamp->isBetween(
                    $this->latestPayroll->cut_off_start->startOfDay(),
                    $this->latestPayroll->cut_off_end->endOfDay()
                );
        });
        
        $seconds = $validatedAttendanceLogs->sum(function ($log) use ($shiftEnd) {
            $end = $shiftEnd->setDateFrom($log->timestamp);

            return $log->timestamp->diffInSeconds($end);
        });

        $total = $seconds > 0;

        if ($total) {
            $hours = floor($seconds / 3600);
            $mins = floor(($seconds % 3600) / 60);

            return (float) "{$hours}.{$mins}";            
        };
    }

    /**
     * Total Tardy Hours
     * 
     * Sum the difference in hours of each employee's assigned start time of shift schedule against
     * his check-in.
     * 
     * @param Employee $employee
     * @return float|null
     */
    public function getTotalTardyHrs(Employee $employee)
    {
        $shiftStart = Carbon::createFromFormat('H:i:s', $employee->shift->start_time);

        $validatedAttendanceLogs = $employee->attendanceLogs->filter(function ($log) {
            return 
                $log->type === BiometricPunchType::CHECK_IN->value &&
                $log->timestamp->isBetween(
                    $this->latestPayroll->cut_off_start->startOfDay(),
                    $this->latestPayroll->cut_off_end->endOfDay()
                );
        });
        
        $seconds = $validatedAttendanceLogs->sum(function ($log) use ($shiftStart) {
            $start = $shiftStart->setDateFrom($log->timestamp);

            return $start->diffInSeconds($log->timestamp);
        });

        $total = $seconds > 0;

        if ($total) {
            $hours = floor($seconds / 3600);
            $mins = floor(($seconds % 3600) / 60);

            return (float) "{$hours}.{$mins}";            
        }
    }
}