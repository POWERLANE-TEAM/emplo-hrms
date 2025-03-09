<?php

namespace App\Livewire\HrManager\Incidents;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

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
