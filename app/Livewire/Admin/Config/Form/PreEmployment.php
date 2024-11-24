<?php

namespace App\Livewire\Admin\Config\Form;

use Livewire\Component;
use App\Enums\UserPermission;
use App\Models\PreempRequirement;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PreEmployment extends Component
{
    #[Validate('required')]
    public $requirement;

    public $index;

    public $editMode = false;
    
    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_PREEMPLOYMENT_REQUIREMENTS)) {
                $this->reset();

                abort(403);
            }
            DB::transaction(function () {
                PreempRequirement::where('preemp_req_id', $this->index)->update([
                    'preemp_req_name' => $this->requirement
                ]);
            });
        } else {
            if (! Auth::user()->hasPermissionTo(UserPermission::UPDATE_PREEMPLOYMENT_REQUIREMENTS)) {
                $this->reset();

                abort(403);
            }
            DB::transaction(function () {
                PreempRequirement::create([
                    'preemp_req_name' => $this->requirement
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
            $this->requirement = $requirement->preemp_req_name;

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
