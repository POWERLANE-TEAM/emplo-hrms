<?php

namespace App\Livewire\Employee\Attendance;

use App\Livewire\Tables\EmployeesAttendanceTable;
use App\Services\AttendanceService;
use Livewire\Component;

class SyncDtrLogs extends Component
{
    public function boot(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;

        $this->attendanceService->storeDtrLogs();

        $this->dispatch('syncToDtrTable')->to(EmployeesAttendanceTable::class);
    }

    public function render()
    {
        return view('livewire.employee.attendance.sync-dtr-logs');
    }
}
