<?php

namespace App\Livewire\Employee\Leaves;

use App\Models\EmployeeLeave;
use Livewire\Attributes\On;
use Livewire\Component;

class Approvals extends Component
{
    public EmployeeLeave $leave;

    public function mount()
    {
        $this->leave->loadMissing([
            'initialApprover',
            'secondaryApprover',
            'thirdApprover',
            'fourthApprover',
            'deniedBy',
            'initialApprover.jobTitle',
            'secondaryApprover.jobTitle',
            'thirdApprover.jobTitle',
            'fourthApprover.jobTitle',
            'deniedBy.jobTitle',
        ]);
    }

    #[On('leaveRequestApproved')]
    public function render()
    {
        return view('livewire.employee.leaves.approvals');
    }
}
