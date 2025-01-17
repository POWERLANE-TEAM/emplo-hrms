<?php

namespace App\Livewire\Employee\Separation;

use App\Models\Employee;
use App\Models\Resignation as ModelsResignation;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Resignation extends Component
{
    #[Locked]
    public bool $hasResignation;

    public Employee $employee;

    public ?ModelsResignation $resignation;

    public function mount()
    {
        $this->employee = auth()->user()->account;

        $this->resignation = optional(optional(auth()->user()->account->resignations())->latest())->first();

        $this->hasResignation = $this->employee->resignations()->exists();

        if ($this->hasResignation) {
            $this->resignation->loadMissing('resigneeLifecycle', 'resignationLetter');
        }

    }

    public function render()
    {
        return view('livewire.employee.separation.resignation');
    }
}
