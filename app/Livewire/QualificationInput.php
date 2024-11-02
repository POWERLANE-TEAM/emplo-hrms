<?php

namespace App\Livewire;

use Livewire\Component;

class QualificationInput extends Component
{
    public $label;
    public $required = false;
    public $id;
    public $name;
    public $options = [];

    public function mount($label, $id, $name, $options = [], $required = false)
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->options = $options;
        $this->required = $required;
    }

    public function render()
    {
        return view('livewire.qualification-input');
    }
}