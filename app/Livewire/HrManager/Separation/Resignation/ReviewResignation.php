<?php

namespace App\Livewire\HrManager\Separation\Resignation;

use Livewire\Component;

class ReviewResignation extends Component
{
    public function saveApproval()
    {
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Resignation approved successfully.',
        ]);

        $this->dispatch('changes-saved', ['modalId' => 'confirmApproval']);
    }

    public function saveRejection()
    {
        $this->dispatch('show-toast', [
            'type' => 'danger',
            'message' => 'Resignation rejected.',
        ]);

        $this->dispatch('changes-saved', ['modalId' => 'confirmRejection']);
    }

    public function render()
    {
        return view('livewire.hr-manager.separation.resignation.review-resignation');
    }
}
