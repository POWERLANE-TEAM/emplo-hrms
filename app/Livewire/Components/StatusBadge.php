<?php

namespace App\Livewire\Components;

use Livewire\Component;

class StatusBadge extends Component
{
    public $color = '';
    public $content = '';

    public function render()
    {
        return view('livewire.components.status-badge');
    }
}
