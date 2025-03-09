<?php

namespace App\Livewire\Calendar;

use App\Models\Holiday;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Holidays extends Component
{
    #[Computed]
    public function holidays()
    {
        return Holiday::all();
    }

    public function render()
    {
        return view('livewire.calendar.holidays');
    }
}
