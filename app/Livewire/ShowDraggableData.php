<?php

namespace App\Livewire;

use Livewire\Component;

class ShowDraggableData extends Component
{
    public $items;

    public function saveChanges($itemName, $index)
    {
        // Logic to save the changes made to the item
        if ($index !== null) {
            $this->items[$index] = $itemName; // Update the item in the list
            $this->emit('itemUpdated'); // Optional: Emit an event if you want to listen for updates elsewhere
        }
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            $this->swapItems($index, $index - 1);
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->items) - 1) {
            $this->swapItems($index, $index + 1);
        }
    }

    private function swapItems($indexA, $indexB)
    {
        $temp = $this->items[$indexA];
        $this->items[$indexA] = $this->items[$indexB];
        $this->items[$indexB] = $temp;
    }

    public function render()
    {
        return view('livewire.show-draggable-data');
    }
}
