<?php

namespace App\Livewire\Admin\JobTitle;

use App\Enums\JobQualificationPriorityLevel;
use App\Models\JobTitle;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class EditEducation extends Component
{
    public JobTitle $jobTitle;

    public $items = [];

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

    public $existingIndex;

    public function save()
    {
        $this->validate();

        if ($this->isEditMode) {
            $this->items[$this->state['index']]['qualification'] = $this->editState['qualification'];
            $this->items[$this->state['index']]['priority'] = $this->editState['priority'];

            $this->dispatch('closeEducationQualificationModal');
            $this->dispatch('updatedEducationQualification',
                $this->state['index'],
                $this->editState['qualification'],
                $this->editState['priority'],
            )->to(JobTitleDetails::class);
        } else {
            $this->items[] = [
                'qualification' => $this->state['qualification'],
                'priority' => $this->state['priority'],
            ];

            $this->dispatch('addedEducationQualification',
                $this->state['qualification'],
                $this->state['priority'],
            )->to(JobTitleDetails::class);
        }

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Education qualification updated!',
        ]);

        $this->resetExcept('items', 'jobTitle');
        $this->resetErrorBag();
    }

    public function saveExisting()
    {
        $this->existingIndex->keyword = $this->editState['qualification'];
        $this->existingIndex->priority = $this->editState['priority'];

        $this->existingIndex->save();

        $this->dispatch('closeEducationQualificationModal');

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Education qualification updated!',
        ]);

        $this->resetExcept('items', 'jobTitle');
        $this->resetErrorBag();
    }

    public function openEditMode(int $index)
    {
        $this->isEditMode = true;
        $this->state['index'] = $index;
        $this->editState['qualification'] = $this->items[$index]['qualification'];
        $this->editState['priority'] = $this->items[$index]['priority'];

        $this->dispatch('openEducationQualificationModal');
    }

    public function openExistingEditMode(int $index)
    {
        $this->isEditMode = true;

        $this->existingIndex = $this->jobTitle->educations->where('keyword_id', $index)->first();

        $this->editState['qualification'] = $this->existingIndex->keyword;
        $this->editState['priority'] = $this->existingIndex->priority;

        $this->dispatch('openExistingEducationQualificationModal');
    }

    public function discard()
    {
        $this->isEditMode = false;
        $this->dispatch('closeEducationQualificationModal');
    }

    public function removeExistingQualification(int $index)
    {
        $this->isEditMode = false;

        $this->jobTitle->educations
            ->where('keyword_id', $index)
            ->first()
            ->delete();

        $this->dispatch('removedExistingEducation')->self();
    }

    public function removeQualification(int $index)
    {
        unset($this->items[$index]);

        $this->dispatch('removeEducationQualification', $index)
            ->to(JobTitleDetails::class);
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

    #[On('removedExistingEducation')]
    public function render()
    {
        return view('livewire.admin.job-title.edit-education');
    }
}
