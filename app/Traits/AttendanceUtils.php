<?php

namespace App\Traits;

use App\Enums\BiometricPunchType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

trait AttendanceUtils
{
    /**
     * Filter attendance logs within time window of regular shift start - end time.
     *
     * @return Collection<int, mixed>
     */
    public function validateRegularLogs(Collection $attendanceLogs)
    {
        $sortedLogs = $attendanceLogs->sortBy('timestamp')->values();

        return $sortedLogs->filter(function ($log) {
            $regShiftStart = Carbon::createFromFormat('H:i:s', $this->regularShift->start_time)
                ->setDateFrom($log->timestamp); // 6 am
            $regShiftEnd = Carbon::createFromFormat('H:i:s', $this->regularShift->end_time)
                ->setDateFrom($log->timestamp); // 10 pm

            return $log->timestamp->isBetween(
                $regShiftStart,
                $regShiftEnd
            );
        });
    }

    /**
     * Filter attendance logs within time window of night differential shift start - end time.
     *
     * @return Collection<int, mixed>
     */
    public function validateNightDiffLogs(Collection $attendanceLogs)
    {
        $checkIns = collect([]);

        $sortedLogs = $attendanceLogs->sortBy('timestamp')->values();

        // case 1: ci = 2/6/25 9 am - co = 2/6/25 5 pm; expected = X actual = X
        // case 2: ci = 2/6/25 10 pm - co = 2/7/25 6 am; expected = ✔ actual = ✔
        // case 3: ci = 2/6/25 10 pm - co = 2/6/25 11 pm; expected = ✔ actual = ✔
        return $sortedLogs->filter(function ($log) use (&$checkIns) {
            $nightDiffShiftStart = Carbon::createFromFormat('H:i:s', $this->nightDifferentialShift->start_time)
                ->setDateFrom($log->timestamp); // 10 pm
            $nightDiffShiftEnd = Carbon::createFromFormat('H:i:s', $this->nightDifferentialShift->end_time)
                ->setDateFrom($log->timestamp->copy()->addDay()); // 6 am

            if ($log->type === BiometricPunchType::CHECK_IN->value &&
                $log->timestamp->gte($nightDiffShiftStart)) {
                $checkIns->push($log->timestamp);
            } elseif ($log->type === BiometricPunchType::CHECK_OUT->value) {
                $corCheckIn = $checkIns->first(function ($checkIn) use ($log) {
                    return
                        $checkIn->isSameDay($log->timestamp) &&
                        $checkIn->isBefore($log->timestamp);
                });

                if ($corCheckIn) {
                    $nightDiffShiftEnd->setDateFrom($log->timestamp)->endOfDay();
                } else {
                    $nightDiffShiftStart->subDay();
                }
            } else {
                return false;
            }

            return $log->timestamp->isBetween(
                $nightDiffShiftStart,
                $nightDiffShiftEnd
            );
        });
    }

    /**
     * Filter attendance logs within time start and end of payroll period.
     *
     * @return Collection<int, mixed>
     */
    public function validateAttendanceLogsPayroll(Collection $attendanceLogs, Carbon $startDate, Carbon $endDate)
    {
        return $attendanceLogs->filter(function ($log) use ($startDate, $endDate) {
            return $log->timestamp->isBetween($startDate->startOfDay(), $endDate->endOfDay());
        });
    }

    /**
     * Add the difference in hours of each check-in against check-out timestamp.
     *
     * @return float|null
     */
    public function sumDtrLogs(Collection $attendanceLogs)
    {
        $totalSecs = $attendanceLogs->sum(function ($logs) {
            $checkIn = null;
            $seconds = 0;

            $logs->each(function ($log) use (&$checkIn, &$seconds) {
                if ($log->type === BiometricPunchType::CHECK_IN->value) {
                    $checkIn = $log->timestamp;
                } elseif ($log->type === BiometricPunchType::CHECK_OUT->value && $checkIn) {
                    $checkOut = $log->timestamp;
                    $seconds += $checkIn->diffInSeconds($checkOut);

                    $checkIn = null;
                }
            });

            return $seconds;
        });

        $total = $totalSecs > 0;

        if ($total) {
            $hours = floor($totalSecs / 3600);
            $mins = round(($totalSecs % 3600) / 60);

            return (float) "{$hours}.{$mins}";
        }
    }

    public function getPunchesTime(Collection $group)
    {
        return $group->reduce(function ($carry, $log) {
            $time = Carbon::parse($log->timestamp)->format('g:i A');

            match ($log->type) {
                BiometricPunchType::CHECK_IN->value => $carry->checkIn = $time,
                BiometricPunchType::CHECK_OUT->value => $carry->checkOut = $time,
            };

            return $carry;
        }, (object) [
            'checkIn' => null,
            'checkOut' => null,
        ]);
    }

    public function getTotalWorkdaysInMonth($date, $holidays): int
    {
        $startDate = Carbon::createFromFormat('Y-m', $date)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $workdays = 0;
        $currentDate = $startDate;

        $holidays = $holidays->filter(function ($holiday) use ($startDate, $endDate) {
            $date = Carbon::createFromFormat('m-d', $holiday->date)->setYear($startDate->year);

            return $date->isBetween($startDate, $endDate);
        })
            ->pluck('date')
            ->toArray();

        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday() && ! in_array($currentDate->toDateString(), $holidays)) {
                $workdays++;
            }
            $currentDate->addDay();
        }

        return $workdays;
    }
}
