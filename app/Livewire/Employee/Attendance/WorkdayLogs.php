<?php

namespace App\Livewire\Employee\Attendance;

use Livewire\Component;

class WorkdayLogs extends Component
{

    public $period;
    protected $listeners = ['periodSelected'];


    public function mount()
    {
        logger()->info('Workday Logs Mount:', ['period' => $this->period]);
    }

    public function periodSelected($period)
    {
        $this->period = $period;
        logger()->info('Workday Logs Received Period:', ['period' => $period]);
    }

    public function render()
    {
        return view('livewire.employee.attendance.workday-logs');
    }
}
