<?php

namespace App\Livewire\Guest;

use App\Models\Position;
use Livewire\Attributes\On;
use Livewire\Component;


class JobViewPane extends Component
{
    private $position;

    #[On('job-selected')]
    public function showPosition($position)
    {
        $this->placeholder();
        $this->position = new Position($position);
        // dd($this->position);
    }

    #[On('job-searched')]
    public function resetView()
    {
        $this->position = null;
    }

    public function placeholder()
    {
        return view('livewire.placeholder.job-view-pane');
    }

    public function render()
    {
        return view('livewire.guest.job-view-pane', ['position' => $this->position, 'lazy' => true]);
    }

    public function rendered()
    {
        $this->dispatch('guest-job-view-pane-rendered');
    }
}
