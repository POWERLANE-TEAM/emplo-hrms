<?php

namespace App\Livewire\Employee;

use Livewire\Component;

class PreEmploymentDoc extends Component
{
    public $pre_employment_doc;

    public function mount($pre_employment_doc)
    {
        $this->pre_employment_doc = $pre_employment_doc;
    }

    public function render()
    {
        return view('livewire.employee.pre-employment-doc');
    }
}
