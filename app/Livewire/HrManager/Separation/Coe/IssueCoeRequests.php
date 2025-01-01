<?php

namespace App\Livewire\HrManager\Separation\Coe;

use Livewire\Component;

class IssueCoeRequests extends Component
{
    public function save()
    {
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Certificate generated successfully',
        ]);
    }

    public function render()
    {
        return view('livewire.hr-manager.separation.coe.issue-coe-requests');
    }
}
