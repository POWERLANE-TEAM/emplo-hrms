<?php

namespace App\Livewire\Admin\Config\Form;

use App\Models\PreempRequirement;
use Livewire\Component;
use Livewire\Attributes\Computed;

class PreEmployment extends Component
{
    #[Computed]
    public function requirements()
    {
        return PreempRequirement::all()->pluck('preemp_req_name')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.config.form.pre-employment');
    }
}
