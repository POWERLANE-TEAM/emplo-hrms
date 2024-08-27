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

    // #[On('job-searched')]
    // public function updateOnSearch($positions = null)
    // {
    //     $this->positions = collect($positions)->map(function ($position) {
    //         return new Position($position);
    //     }); /* Convert back to collection cause wtf  livewire converts model instance to an array */
    //     $this->isFiltered = true;
    // }

    public function render()
    {

        if (!$this->isFiltered) {
            $this->positions = Position::latest()->get();
        }

        return view('livewire.guest.jobs-list-card', ['positions' => $this->positions]);
    }
}
