<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Traits\AttendanceUtils;
use Livewire\Attributes\Locked;
use App\Services\AttendanceService;

class DailyTimeRecord extends Component
{
    use AttendanceUtils;

    private $attendanceService;

    #[Locked]
    public $dtrLogs;

    #[Locked]
    public $totalDtr;

    public function boot(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;

        // $this->attendanceService->storeDtrLogs(); // uncomment if bio machine is connected

        $this->dtrLogs = $this->attendanceService->getDtrLogs();
        $this->totalDtr = $this->dtrLogs->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.daily-time-record');
    }
}
