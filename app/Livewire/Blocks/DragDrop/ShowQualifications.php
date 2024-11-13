<?php

namespace App\Livewire\Blocks\DragDrop;

use App\Livewire\Admin\JobTitle\CreateJobTitleForm;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Enums\JobQualificationPriorityLevel;

class ShowQualifications extends Component
{
    public $items = [];
    
    public $eventName = 'save-changes';

    public $priority;

    public $qualification;

    public $index;

    #[Computed]
    public function priorityLevels()
    {
        return collect(JobQualificationPriorityLevel::options())->flip()->toArray();
    }

    #[On('qualification-added')]
    public function showQualifications($qualification, $priority)
    {
        $this->items[] = [
            'text' => $qualification,
            'priority' => $priority,
        ];
    }

    #[On('save-changes')]
    public function saveChanges()
    {
        $this->items[$this->index]['text'] = $this->qualification;
        $this->items[$this->index]['priority'] = $this->priority;

        $this->dispatch('save-changes-close');
        $this->dispatch('qualification-updated',
            $this->index, 
            $this->qualification, 
            $this->priority
        )->to(CreateJobTitleForm::class);
    }

    #[On('job-title-created')]
    public function resetQualifications()
    {
        $this->items = [];
    }

    public function loadQualification($index)
    {
        $this->index = $index;
        $this->qualification = $this->items[$index]['text'];
        $this->priority = $this->items[$index]['priority'];

        $this->dispatch('open-modal');
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
        return view('livewire.blocks.dragdrop.show-qualifications');
    }
}
