<?php

namespace App\Livewire\Admin\JobTitle;

use App\Enums\JobQualificationPriorityLevel;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SetExperience extends Component
{
    public $items = [];

    #[Validate]
    public $state = [
        'priority' => null,
        'qualification' => null,
    ];

    public $isEditMode = false;

    public $editState = [
        'index' => null,
        'priority' => null,
        'qualification' => null,
    ];

    #[On('job-title-created')]
    public function resetQualifications()
    {
        $this->items = [];
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditMode) {
            $this->items[$this->state['index']]['qualification'] = $this->editState['qualification'];
            $this->items[$this->state['index']]['priority'] = $this->editState['priority'];

            $this->dispatch('close-qualification-modal');
            $this->dispatch('qualification-updated',
                $this->state['index'],
                $this->editState['qualification'],
                $this->editState['priority'],
            )->to(CreateJobTitleForm::class);
        } else {
            $this->items[] = [
                'qualification' => $this->state['qualification'],
                'priority' => $this->state['priority'],
            ];

            $this->dispatch('qualification-added',
                $this->state['qualification'],
                $this->state['priority'],
            )->to(CreateJobTitleForm::class);
        }

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Experience qualification added!',
        ]);

        $this->resetExcept('items');
        $this->resetErrorBag();
    }

    public function openEditMode(int $index)
    {
        $this->isEditMode = true;
        $this->state['index'] = $index;
        $this->editState['qualification'] = $this->items[$index]['qualification'];
        $this->editState['priority'] = $this->items[$index]['priority'];

        $this->dispatch('open-qualification-modal');
    }

    public function discard()
    {
        $this->isEditMode = false;
        $this->dispatch('close-qualification-modal');
    }

    public function rules()
    {
        if ($this->isEditMode) {
            return [
                'editState.priority' => 'required',
                'editState.qualification' => 'required',
            ];
        }

        return [
            'state.priority' => 'required',
            'state.qualification' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'state.priority' => __('Priority is required'),
            'state.qualification' => __('Qualification is required'),
            'editState.priority' => __('Priority is required for editing'),
            'editState.qualification' => __('Qualification is required for editing'),
        ];
    }

    #[Computed]
    public function priorityLevels()
    {
        return collect(JobQualificationPriorityLevel::options())->flip()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.job-title.set-experience');
    }
}
