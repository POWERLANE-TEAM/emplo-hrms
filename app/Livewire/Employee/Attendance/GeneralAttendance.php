<?php

namespace App\Livewire\Employee\Attendance;

use Livewire\Component;

class GeneralAttendance extends Component
{

    public $selectedView = 'summary';
    public $selectedPeriod = '1'; // Default to most recent period

    public function render()
    {
        return view('livewire.employee.attendance.general-attendance', [
            'currentPeriod' => $this->selectedPeriod
        ]);
    }

    public function updatedSelectedPeriod()
    {
        logger()->info('Period Updated:', ['period' => $this->selectedPeriod]);
        $this->dispatch('periodSelected', [$this->selectedPeriod]);
    }
}
