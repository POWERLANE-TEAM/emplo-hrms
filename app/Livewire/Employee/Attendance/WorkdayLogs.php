<?php

namespace App\Livewire\Employee\Attendance;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;

class WorkdayLogs extends Component
{
    #[Reactive]
    #[Locked]
    public $period;

    public Employee $employee;

    public function render()
    {
        return view('livewire.employee.attendance.workday-logs');
    }
}
