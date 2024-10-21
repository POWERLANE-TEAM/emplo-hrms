<?php

namespace App\Livewire\Employee;

use App\Models\PreempRequirement;
use Livewire\Component;

class PreEmploymentModal extends Component
{

    public PreempRequirement $pre_employment_req;

    public function render()
    {
        return view('livewire.employee.pre-employment-modal');
    }
}
