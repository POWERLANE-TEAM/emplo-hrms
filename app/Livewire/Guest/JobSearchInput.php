<?php

namespace App\Livewire\Guest;

use Livewire\Component;

class JobSearchInput extends Component
{
    public $search;

    public $result = '';

    public function render()
    {
        return view('livewire.guest.job-search-input', ['searched' => $this->result]);
    }
}
