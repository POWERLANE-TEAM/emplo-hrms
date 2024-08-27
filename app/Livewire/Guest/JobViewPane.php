<?php

namespace App\Livewire\Guest;

use Livewire\Component;

class JobViewPane extends Component
{
    private $position;

    public function showPosition($position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('livewire.guest.job-view-pane', ['position' => $this->position]);
    }
}
