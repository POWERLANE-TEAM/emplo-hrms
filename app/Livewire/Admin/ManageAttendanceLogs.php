<?php

namespace App\Livewire\Admin;

use App\Enums\BiometricPunchType;
use Livewire\Component;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Http\Helpers\BiometricDevice;

class ManageAttendanceLogs extends Component
{
    private BiometricDevice $zkInstance;

    public function boot()
    {
        $this->zkInstance = new BiometricDevice();
    }

    private function cleanAttendanceLogs()
    {
        return $this->zkInstance->getRawAttendanceLogs()
            ->groupBy(function ($log) {
                return $log->id . '-' . Carbon::parse($log->timestamp)->toDateString();
            })
            ->map(function ($group) {
                $group = $group->sortBy('timestamp');
    
                $checkIn = null;
                $checkOut = null;
                $overtimeIn = null;
                $overtimeOut = null;

                foreach ($group as $log) {
                    $time = Carbon::parse($log->timestamp)->format('g:i A');
                    match ($log->type) {
                        BiometricPunchType::CHECK_IN->value => $checkIn = $time,
                        BiometricPunchType::CHECK_OUT->value => $checkOut = $time,
                        BiometricPunchType::OVERTIME_IN->value => $overtimeIn = $time,
                        BiometricPunchType::OVERTIME_OUT->value => $overtimeOut = $time,
                    };
                }
                $firstLog = $group->first(); 
    
                return (object) [
                    'uid' => $firstLog->uid,
                    'state' => $firstLog->state,
                    'checkIn' => $checkIn,
                    'checkOut' => $checkOut,
                    'overtimeIn' => $overtimeIn,
                    'overtimeOut' => $overtimeOut,
                    'date' => Carbon::parse($firstLog->timestamp)->format('F d, Y'),
                    'employee' => Employee::find((int) $firstLog->id),
                ];
            });
    }
    
    public function clearAttendanceLogs()
    {
        //
    }

    public function syncAttendanceLogs()
    {
        $this->zkInstance->getRawAttendanceLogs()
            ->each(function ($item) {
                AttendanceLog::create([
                    'uid' => $item->uid,
                    'employee_id' => (int) $item->id,
                    'state' => $item->state,
                    'type' => $item->type,
                    'timestamp' => $item->timestamp,
                ]);
            });
    }

    public function getUsers()
    {
        return $this->zkInstance->getUsers();
    }

    public function render()
    {
        return view('livewire.admin.manage-attendance-logs', [
            'attlogs' => $this->cleanAttendanceLogs(),
        ]);
    }
}
