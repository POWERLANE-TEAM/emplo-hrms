<?php

namespace App\Livewire\Employee;

use App\Models\PreempRequirement;
use Illuminate\Support\Collection;
use Livewire\Component;

class PreEmployment extends Component
{
    public Collection $pre_employment_reqs;

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

        if ($requirements->count() < $this->chunk) {
            $this->dispatch('pre-employment-docs-loaded');
        }

        $this->pre_employment_reqs = ($this->loads == 0) ? $requirements : $this->pre_employment_reqs->merge($requirements);

        return view('livewire.employee.pre-employment', ['pre_employment_reqs' => $this->pre_employment_reqs]);
    }

    public function rendered()
    {
        $this->dispatch('pre-employment-docs-rendered');
    }
}
