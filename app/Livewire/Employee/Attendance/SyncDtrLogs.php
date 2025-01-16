<?php

namespace App\Livewire\Employee\Attendance;

use App\Livewire\Tables\EmployeesAttendanceTable;
use Livewire\Component;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Http\Helpers\BiometricDevice;

class SyncDtrLogs extends Component
{
    private BiometricDevice $zkInstance;

    public function sync()
    {
        $this->zkInstance = new BiometricDevice();
        $this->upsertTodayDtr();

        $this->dispatch('syncToDtrTable')->to(EmployeesAttendanceTable::class);
    }

    private function upsertTodayDtr()
    {
        $this->zkInstance->getRawAttendanceLogs()
            ->filter(fn ($log) => Carbon::parse($log->timestamp)->isSameDay(today()))
            ->each(function ($item) {
                AttendanceLog::upsert([
                    'uid' => $item->uid,
                    'employee_id' => (int) $item->id,
                    'state' => $item->state,
                    'type' => $item->type,
                    'timestamp' => $item->timestamp
                ],
                uniqueBy: ['uid'], 
                update: [
                    'employee_id',
                    'state',
                    'timestamp',
                    'type',
                ]);
            });
    }

    public function render()
    {
        return view('livewire.employee.attendance.sync-dtr-logs');
    }
}
