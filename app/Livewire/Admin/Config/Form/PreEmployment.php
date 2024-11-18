<?php

namespace App\Livewire\Admin\Config\Form;

use Livewire\Component;
use App\Models\PreempRequirement;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

class PreEmployment extends Component
{
    #[Validate([
        'state.newRequirement' => 'required',
        'state.existingRequirement' => 'required',
    ])]
    public $state = [
        'newRequirement' => '',
        'existingRequirement' => '',
    ];

    public $index;

    public $editMode = false;
    
    public function save()
    {
        // $this->validate();

        if ($this->editMode) {
            DB::transaction(function () {
                PreempRequirement::where('preemp_req_id', $this->index)->update([
                    'preemp_req_name' => $this->state['existingRequirement'],
                ]);
            });
        } else {
            DB::transaction(function () {
                PreempRequirement::create([
                    'preemp_req_name' => $this->state['newRequirement'],
                ]);
            });
        }
        $this->dispatch('changes-saved');

        $this->reset();
        $this->resetErrorBag();
    }

    public function openEditMode(int $id)
    {
        $requirement = PreempRequirement::find($id);

        if (! $requirement) {
            // some error msg
        } else {
            $this->editMode = true;
            $this->index = $requirement->preemp_req_id;
            $this->state['existingRequirement'] = $requirement->preemp_req_name;

            $this->dispatch('open-preemployment-modal');
        }
    }

    public function discard()
    {
        if ($this->editMode) {
            $this->reset();
        }

        $this->resetErrorBag();
        $this->dispatch('reset-preemployment-modal');
    }

    #[Computed]
    public function requirements()
    {
        return PreempRequirement::latest()->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item['preemp_req_id'],
                    'name' => $item['preemp_req_name'],
                ];
            }
        );
    }

    public function render()
    {
        return view('livewire.admin.config.form.pre-employment');
    }
}
