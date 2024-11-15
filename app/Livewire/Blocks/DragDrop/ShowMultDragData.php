<?php

namespace App\Livewire\Blocks\Dragdrop;

use Livewire\Component;

class ShowMultDragData extends Component
{

    public $items = [];

    public $editCallback;
    public $head;
    public $subhead;

    public function mount($items, $editCallback = null, $head = null, $subhead = null)
    {
        $this->items = $items;
        $this->editCallback = $editCallback;
        $this->head = $head;
        $this->subhead = $subhead;
    }

    public function addItems($allitems)
    {
        $this->items[] = $allitems;
    }

    public function saveChanges($itemName, $index)
    {
        if ($index !== null) {
            $this->items[$index] = $itemName;
            $this->emit('itemUpdated');
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
        $this->items = $newOrder;
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
        return view('livewire.blocks.dragdrop.show-mult-drag-data');
    }
}
