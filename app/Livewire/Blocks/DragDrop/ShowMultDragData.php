<?php

namespace App\Livewire\Blocks\Dragdrop;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\PerformanceCategory;

class ShowMultDragData extends Component
{

    public $items = [];

    public $editCallback;

    public $eventName;

    public $title;

    #[On('item-added')]
    public function addItems($allitems)
    {
        $this->items[] = [
            'head' => $allitems[0]['head'],
            'subhead' => $allitems[0]['subhead']
        ];
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
        $this->items = $this->categories;

        $head = array_map(function ($item) {
            return "<div class='fw-bold text-primary fs-5'>{$item['head']}</div>";
        }, $this->categories);

        $subhead = array_map(function ($item) {
            return "<div class='text-muted'>{$item['subhead']}</div>";
        }, $this->categories);  

        return view('livewire.blocks.dragdrop.show-mult-drag-data', 
            compact('head', 'subhead')
        );
    }
}
