<?php

namespace App\Livewire\Guest;

use App\Models\Position;
use Livewire\Attributes\On;
use Livewire\Component;

class JobsListCard extends Component
{
    private $positions;
    private $isFiltered = false;

    #[On('job-searched')]
    public function updateOnSearch($search = null)
    {
        if (strlen(trim($search)) >= '1') {
            $result = Position::where('title', 'ilike', '%' . $search . '%')
                ->orWhere('description', 'ilike', '%' . $search . '%')
                ->get();
        } else {
            $result = Position::latest()->get();
        }
        $this->positions = $result;
        $this->isFiltered = true;
    }

    public function placeholder()
    {
        $this->positions = Position::latest()->get();
        return view('livewire.placeholder.job-list-card', ['positions' => $this->positions]);
    }

    public function render()
    {

        if (!$this->isFiltered) {
            $this->positions = Position::latest()->get();
        }

        return view('livewire.guest.jobs-list-card', ['positions' => $this->positions]);
    }
}
