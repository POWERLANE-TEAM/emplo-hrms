<?php

namespace App\Livewire\Employee\Overtimes;

use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class OvertimeSummaryApproval extends Component
{
    #[Locked]
    public $otSummaryInfo;

    public $loading = true;

    #[On('showOvertimeSummaryApproval')]
    public function catchEventPayload(array $eventPayload)
    {
        $this->otSummaryInfo = (object) $eventPayload;

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.employee.overtimes.overtime-summary-approval');
    }
}
