<?php

namespace App\Livewire\HrManager\Incidents;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;

class ShowCollaboratorPhoto extends Component
{
    #[Locked]
    #[Reactive]
    public $incidentCollaborators;

    public function render()
    {
        return view('livewire.hr-manager.incidents.show-collaborator-photo', [
            'collaborators' => $this->incidentCollaborators,
        ]);
    }
}
