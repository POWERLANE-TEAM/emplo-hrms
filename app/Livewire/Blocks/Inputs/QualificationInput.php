<?php

namespace App\Livewire\Blocks\Inputs;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use App\Enums\JobQualificationPriorityLevel;

class QualificationInput extends Component
{
    #[Validate('required')]
    public $qualification;

    #[Validate('required')]
    public $priority;

    public function mount()
    {
        //
    }

    public function save()
    {
        $this->validate();
        $this->dispatch('qualification-added', $this->qualification, $this->priority);
        $this->reset();
    }

    #[Computed]
    public function priorityLevels()
    {
        return collect(JobQualificationPriorityLevel::options())->flip()->toArray();
    }

    public function render()
    {
        return view('livewire.blocks.inputs.qualification-input');
    }
}