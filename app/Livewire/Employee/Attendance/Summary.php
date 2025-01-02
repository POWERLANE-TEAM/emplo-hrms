<?php

namespace App\Livewire\Employee\Attendance;

use Livewire\Component;

class Summary extends Component
{

    public $period;

    protected $listeners = ['periodSelected'];

    public function periodSelected($period)
    {
        $this->period = $period;
        logger()->info('Payroll period selected:', ['period' => $period]);
        // Add any logic you need when period changes
    }

    public function render()
    {
        return view('livewire.employee.attendance.summary');
    }
}
