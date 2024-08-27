<?php

namespace App\Livewire\Guest;

use App\Models\Position;
use Livewire\Component;

class JobSearchInput extends Component
{

    public $search;
    public $result = '';

    public function render()
    {
        // if (strlen(trim($this->search)) >= '1') {
        //     $this->result = Position::where('title', 'ilike', '%' . $this->search . '%')
        //         ->orWhere('description', 'ilike', '%' . $this->search . '%')
        //         ->get();
        // } else {
        //     $this->result = Position::latest()->get();
        // }

        // $this->dispatch('job-searched', $this->result);

        return view('livewire.guest.job-search-input', ['searched' => $this->result]);
    }
}
