<?php

namespace App\Livewire\Applicant;

use App\Models\Position;
use Livewire\Component;

class JobsListCard extends Component
{
    private $positions;

    public function render()
    {

        $this->positions = Position::all();

        return view('livewire.applicant.jobs-list-card', ['positions' => $this->positions]);
    }
}
