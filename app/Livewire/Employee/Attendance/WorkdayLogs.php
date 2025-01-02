<?php

namespace App\Livewire\Employee\Attendance;

use Livewire\Component;

class WorkdayLogs extends Component
{

    public $period;

    protected $listeners = ['periodSelected' => 'updatePeriod'];

    public function updatePeriod($data)
    {
        $this->period = $data['period'];
        // Add any logic you need when period changes
    }

    public function render()
    {
        return view('livewire.employee.attendance.workday-logs');
    }
}
