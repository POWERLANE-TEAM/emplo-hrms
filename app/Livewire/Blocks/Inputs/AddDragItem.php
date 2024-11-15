<?php

namespace App\Livewire\Blocks\Inputs;

use Livewire\Component;

class AddDragItem extends Component
{

    public $label;
    public $id;
    public $name;
    public $required = false;
    
    public function mount($label, $id, $name, $required = false)
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->required = $required;
    }

    public function render()
    {
        return view('livewire.blocks.inputs.add-drag-item');
    }
}
