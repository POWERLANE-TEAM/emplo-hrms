<?php

namespace App\Livewire\Employee\Modal;

use App\Models\PreempRequirement;
use Livewire\Component;

class PreEmploymentPreview extends Component
{

    public PreempRequirement $pre_employment_req;

    public function render()
    {
        return view('livewire.employee.modal.pre-employment-preview');
    }
}
