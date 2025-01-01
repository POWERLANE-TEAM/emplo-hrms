<?php

namespace App\Livewire\HrManager\Incidents;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Incident;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class AddCollaborators extends Component
{
    public Incident $incident;

    public $collaborators = [];

    public function mount()
    {
        $this->incident->loadMissing([
            'collaborators',
            'collaborators.account',
        ]);
    }

    public function save()
    {
        // authorize

        $newCollaboratorsAccess = $this->formatArray();

        $newCollaborators = array_combine(array_keys($this->collaborators), $newCollaboratorsAccess);

        DB::transaction(function () use ($newCollaborators) {
            $this->incident->collaborators()->attach($newCollaborators);

        });
        
        $this->resetExcept('incident');

        $this->dispatch('addedNewCollaborators', [
            'type' => 'success',
            'message' => __("New collaborator/s were added successfully.")
        ]);
    }

    public function updateCollaboratorAccess(Employee $employee)
    {
        $collaborator = $this->formatArray();

        $this->incident->collaborators()->syncWithoutDetaching($collaborator);

        $this->resetExcept('incident');

        $this->dispatch('updatedCollaboratorAccess', [
            'type' => 'success',
            'message' => __("{$employee->last_name}'s access has been updated.")
        ]);
    }

    private function formatArray()
    {
        return array_map(function ($access) {
            return ['is_editor' => $access === 'editor'];
        }, $this->collaborators);
    }

    public function rules()
    {
        // the employee must exists
    }

    public function messages()
    {
        // idk
    }

    #[Computed]
    public function employees()
    {
        $collaborators = $this->incident->collaborators()
            ->pluck('employees.employee_id');
        
        return Employee::whereNotIn('employee_id', $collaborators)
            ->get();
    }

    #[Computed]
    public function incidentCollabs()
    {
        return $this->incident->collaborators;
    }

    public function render()
    {
        return view('livewire.hr-manager.incidents.add-collaborators');
    }
}
