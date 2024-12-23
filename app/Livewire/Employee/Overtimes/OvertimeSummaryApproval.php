<?php

namespace App\Livewire\Employee\Overtimes;

use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Attributes\On;

class OvertimeSummaryApproval extends Component
{
    #[Locked]
    public $otSummaryInfo;

    public $loading = true;

    #[On('showOvertimeSummaryApproval')]
    public function catchEventPayload(array $eventPayload) {
        $this->otSummaryInfo = (object) $eventPayload;

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.employee.overtimes.overtime-summary-approval');
    }
}
