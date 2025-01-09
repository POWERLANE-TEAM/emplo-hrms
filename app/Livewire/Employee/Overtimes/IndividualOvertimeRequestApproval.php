<?php

namespace App\Livewire\Employee\Overtimes;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class IndividualOvertimeRequestApproval extends Component
{
    #[Locked]
    public $otRequest;

    public $loading = true;
    
    #[On('showOvertimeRequestApprovalInfo')]
    public function catchEventPayload(array $eventPayload)
    {
        $this->otRequest = (object) array_map(fn ($item) => (object) $item, $eventPayload);

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.employee.overtimes.individual-overtime-request-approval');
    }
}
