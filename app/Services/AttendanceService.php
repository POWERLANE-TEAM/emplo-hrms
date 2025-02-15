<?php

namespace App\Services;

use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Traits\AttendanceUtils;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\BiometricDevice;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService
{
    use AttendanceUtils;

    public $storedDtrLogs;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->storedDtrLogs = AttendanceLog::today()->get();
    }

    /**
     * Store Daily Time Record Logs
     * 
     * Get raw attendance logs from the ZKTeco bio machine, validate for duplicate check-ins and 
     * check-outs for each employee, and store it in `attendance_logs` table.
     * 
     * @return void
     */
    public function storeDtrLogs(): void
    {
        $zkInstance = new BiometricDevice;

        $todayLogs = $zkInstance->getRawAttendanceLogs()
            ->filter(fn ($log) => Carbon::parse($log->timestamp)->isSameDay(today()))
            ->sortBy('timestamp');

        if ($todayLogs->isEmpty()) return;

        $newLogs = collect([]);

        foreach ($todayLogs as $todayLog) {
            $contains = $newLogs->contains(function ($log) use ($todayLog) {
                return 
                    (int) $todayLog->id === $log['employee_id'] &&
                    $todayLog->type === $log['type'];
            });

            $exists = $this->storedDtrLogs->where('employee_id', (int) $todayLog->id)
                                        ->where('type', $todayLog->type)
                                        ->first();

            if ($contains || $exists) continue;

            $newLogs->push([
                'employee_id'   => (int) $todayLog->id,
                'state'         => $todayLog->state,
                'type'          => $todayLog->type,
                'timestamp'     => $todayLog->timestamp  
            ]);
        }

        if ($newLogs->isNotEmpty()) {
            DB::transaction(fn () => AttendanceLog::insert($newLogs->toArray()));
        }
    }

    /**
     * Get daily time record logs for today only.
     * 
     * @return Collection<int|string, object>
     */
    public function getDtrLogs()
    {
        return AttendanceLog::today()->with([
            'employee',
            'employee.account',
        ])
            ->get()
            ->groupBy('employee_id')
            ->map(function ($group) {
                $type = $this->getPunchesTime($group);
                $punch = $group->first();
                $employee = $punch->employee;

                return (object) [
                    'checkIn' => $type->checkIn,
                    'checkOut' => $type->checkOut,
                    'employee' => optional($employee, function ($puncher) {
                        return (object) [
                            'id' => $puncher->employee_id,
                            'name' => $puncher->full_name,
                            'photo' => $puncher->account->photo,
                        ];
                    }),
                ];
            });
    }

    public function clearDtrLogs()
    {
        $zkInstance = new BiometricDevice;

        $zkInstance->clearAttendanceLogs();
    }
}