<?php

namespace App\Livewire\Blocks\Inputs;

use Livewire\Component;

class QualificationInput extends Component
{
    public $label;
    public $id;
    public $name;
    public $required;
    public $options = [];
    
    public $qualificationText;
    public $selectedPriority;
    
    protected $listeners = ['qualificationAdded'];

    public function mount($label, $id, $name, $required = false, $options = [])
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->required = $required;
        $this->options = $options;
    }

    public function addQualification()
    {
        if ($this->qualificationText && $this->selectedPriority) {
            // Emit event with qualification and priority
            $this->emit('qualificationAdded', [
                'text' => $this->qualificationText,
                'priority' => $this->selectedPriority
            ]);

            // Reset input fields
            $this->qualificationText = '';
            $this->selectedPriority = '';
        }
    }

    public function render()
    {
        return view('livewire.blocks.inputs.qualification-input');
    }
}