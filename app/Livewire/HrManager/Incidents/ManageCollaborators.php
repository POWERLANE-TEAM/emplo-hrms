<?php

namespace App\Livewire\HrManager\Incidents;

use App\Models\User;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Incident;
use App\Enums\UserPermission;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class ManageCollaborators extends Component
{
    public Incident $incident;

    #[Locked]
    public $routePrefix;

    public $collaborators = [];

    public $searchQuery = '';

    public $searchQueryResult = [];

    public function mount()
    {
        $this->incident->loadMissing([
            'collaborators',
            'collaborators.account',
            'reportedBy',
            'reportedBy.account',
        ]);
    }

    /**
     * Check for the ff:
     * 
     * - employes's full name (case-insensitive)
     * - reject account with permission (backed enum) "manage incident report collaborators"
     * - reject employee's who are already added as collaborators
     * - and reject the reporter itself.
     * 
     * @return void
     */
    public function updatedSearchQuery()
    {
        if (empty($this->searchQuery)) {
            $this->reset('searchQueryResult'); 
        } else {
            $this->searchQueryResult = Employee::where(function ($query) {
                $query->whereLike('last_name', "%{$this->searchQuery}%")
                    ->orWhereLike('middle_name', "%{$this->searchQuery}%")
                    ->orWhereLike('first_name', "%{$this->searchQuery}%");    
                })
                ->whereHas('account', function ($query) {
                    $query->withoutPermission(UserPermission::MANAGE_INCIDENT_REPORT_COLLABORATORS);
                })
                ->whereNotIn('employee_id', $this->existingCollaborators)
                ->limit(10)
                ->get()
                ->reject(fn ($item) => $this->incident->reporter === $item->employee_id); 
        }
    }

    /**
     * Store new collaborators.
     * 
     * @return void
     */
    public function save()
    {
        $this->authorize('manageIncidentReportCollaborators');

        // $this->validate();

        $newCollaboratorsAccess = array_map(function ($access) {
            return ['is_editor' => $access === 'editor'];
        }, $this->collaborators);

        $newCollaborators = array_combine(array_keys($this->collaborators), $newCollaboratorsAccess);

        DB::transaction(function () use ($newCollaborators) {
            $this->incident->collaborators()->attach($newCollaborators);
        });
        
        $this->resetExcept('incident', 'routePrefix');

        $this->dispatchEventWithPayload('addedNewCollaborators', [
            'type' => 'success',
            'message' => __("New collaborator/s were added successfully.")
        ]);
    }

    /**
     * Update existing collaborator's access (upgrade, downgrade, or removal).
     * 
     * @param \App\Models\Employee $collaborator
     * @param string $access
     * @return void
     */
    public function updateCollaboratorAccess(Employee $collaborator, string $access)
    {
        $this->authorize('manageIncidentReportCollaborators');

        // $this->validate();

        DB::transaction(function () use ($collaborator, $access) {
            if ($access === 'remove') {
                $this->incident->collaborators()->detach($collaborator);

                $this->dispatch('updatedCollaboratorAccess', [
                    'type' => 'info',
                    'message' => __("{$collaborator->last_name}'s access has been removed.")
                ]);
            } else {
                $this->incident->collaborators()->updateExistingPivot($collaborator, [
                    'is_editor' => $access === 'editor'
                ]);

                $this->dispatch('updatedCollaboratorAccess', [
                    'type' => 'success',
                    'message' => __("{$collaborator->last_name}'s access has been updated.")
                ]);
            }
        });

        $this->resetExcept('incident', 'routePrefix');
    }

    private function dispatchEventWithPayload(string $eventName, array $eventPayload)
    {
        $this->dispatch($eventName, $eventPayload);
    }

    public function rules(): array
    {
        return [
            'collaborators' => 'required|array',
            // 'collaborators.*' => 'required_array_keys|exists:employees,employee_id',
        ];
    }

    public function messages()
    {
        // idk
    }

    #[Computed]
    public function existingCollaborators()
    {
        return $this->incident->collaborators()
            ->pluck('employees.employee_id');
    }

    #[Computed]
    public function incidentCollaborators()
    {
        $higherAuthorities = User::query()
            ->with('account')
            ->permission(UserPermission::MANAGE_INCIDENT_REPORT_COLLABORATORS)
            ->get();

        $reporter = $this->incident->reportedBy;   
        $collaborators = $this->incident->collaborators;

        return collect([$reporter, $higherAuthorities])->merge($collaborators);
    }

    public function render()
    {
        return view('livewire.hr-manager.incidents.manage-collaborators');
    }
}
