<?php

namespace App\Livewire\Employee;

use App\Models\PreempRequirement;
use Livewire\Component;

class PreEmployment extends Component
{
    public $pre_employment_reqs;

    public $loads = 0;

    public $chunk = 5;

    public $additional_docs;

    public function loadMore()
    {
        $this->loads++;
    }

    public function render()
    {
        $requirements = PreempRequirement::offset($this->chunk * $this->loads)->limit($this->chunk)->get();
        $this->pre_employment_reqs = ($this->loads == 0) ? $requirements : $this->pre_employment_reqs->merge($requirements);

        // if (empty($this->pre_employment_docs)) {
        //     return;
        // }

        return view('livewire.employee.pre-employment', ['pre_employment_reqs' => $this->pre_employment_reqs]);
    }

    public function rendered()
    {
        $this->dispatch('pre-employment-docs-rendered');
    }
}
