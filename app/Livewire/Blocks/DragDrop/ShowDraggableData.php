<?php

namespace App\Livewire\Blocks\DragDrop;

use Livewire\Component;

class ShowDraggableData extends Component
{
    public $items;

    public function saveChanges($itemName, $index)
    {
        // Logic to save the changes made to the item
        if ($index !== null) {
            $this->items[$index] = $itemName; // Update the item in the list
            $this->dispatch('itemUpdated'); // Optional: Emit an event if you want to listen for updates elsewhere
        }
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            $this->swapItems($index, $index - 1);
        }
    }

    public function updateItems($newOrder)
    {
        // Update the items array based on the new order received
        $this->items = $newOrder;

        // Optionally, you can save the new order to the database here
        // For example, if you have a model for this data
        // YourModel::updateOrder($this->items);
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
        return view('livewire.blocks.dragdrop.show-draggable-data');
    }
}
