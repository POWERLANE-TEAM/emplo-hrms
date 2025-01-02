<?php

namespace App\Livewire\Employee\Attendance;

use Livewire\Component;

class Summary extends Component
{

    public $period;
    protected $listeners = ['periodSelected'];


    public function mount()
    {
        logger()->info('Summary Mount:', ['period' => $this->period]);
    }

    public function periodSelected($period)
    {
        $this->period = $period;
        logger()->info('Summary Received Period:', ['period' => $period]);
    }

    public function render()
    {
        return view('livewire.employee.attendance.summary');
    }
}
