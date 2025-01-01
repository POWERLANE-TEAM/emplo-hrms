@props([
    'modalId' => 'shareWithOthersModal',
])

<div>
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title" id="{{ $modalId }}">
                {{ __('Share this incident report') }}
            </h1>
            <button data-bs-dismiss="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="fw-medium">{{ __('Invite others to review and collaborate with this incident report.') }}</div>    
            <div class="dropdown my-3">
                <button class="text-start fw-light py-2 text-secondary-emphasis btn bg-body border-dark-subtle dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div>
                            <i class="icon icon-large me-2" data-lucide="search"></i>
                        </div>
                        <div>
                            {{ __('Select for collaborators') }}                        
                        </div>
                    </div>
                </button>
                <ul class="dropdown-menu w-100" role="menu">
                    @foreach ($this->employees as $employee)
                        <li 
                            x-data="{ showDropdown: false }"
                            @click.stop="showDropdown = !showDropdown"
                            @mouseenter="showDropdown = true"
                            @mouseleave="showDropdown = false"
                            wire:dirty.class="border  border-warning-subtle"
                            wire:target="collaborators.{{ $employee->employee_id }}"
                            class="dropdown-item py-2" 
                            role="menuitem" 
                        >
                            <div class="d-flex align-items-center">
                                <input type="hidden"
                                    role="button"
                                    id="selectCollaborator{{ $employee->employee_id }}" 
                                    wire:model="collaborators.{{ $employee->employee_id }}" 
                                    value="{{ $employee->employee_id }}" 
                                    multiple
                                >
                
                                <label class="d-flex align-items-center w-100" for="selectCollaborator{{ $employee->employee_id }}">
                                    <img src="{{ $employee->account->photo }}" 
                                        class="rounded-circle me-3"
                                        alt="employee photo" 
                                        width="35" 
                                        height="35"
                                    />
                                    <div>
                                        <div class="fw-medium">{{ $employee->full_name }}</div>
                                        <div class="text-muted fs-6">{{ $employee->account->email }}</div>
                                    </div>
                                </label>
                
                                <div x-show="showDropdown" @click.stop x-cloak x-transition class="mt-2 w-25">
                                    <select wire:model="collaborators.{{ $employee->employee_id }}" class="form-select">
                                        <option class="text-center" value="" selected>{{ __('Access Level') }}</option>
                                        <option class="text-center" value="viewer">{{ __('Viewer') }}</option>
                                        <option class="text-center" value="editor">{{ __('Editor') }}</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                    @endforeach

                    <div class="m-2 d-flex align-items-center justify-content-between">
                        <div wire:dirty class="me-auto ps-2 fw-medium text-warning" wire:target="collaborators">
                            {{ __('Unsaved changes...') }}
                        </div> 
                        <div class="ms-auto">
                            <button 
                                type="button" 
                                class="fs-7 px-3 btn btn-primary fw-light" 
                                wire:click="save" 
                                onclick="event.stopPropagation()"
                            > {{ __('Done') }}
                            </button> 
                        </div>
                    </div>
                </ul>
            </div>

            <div>
                <div class="fw-medium mb-2">{{ __('People with access') }}</div>
                <ul class="list-unstyled w-100">
                    @foreach ($this->incidentCollabs as $collaborator)
                    <li class="py-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ $collaborator->account->photo }}" 
                                    class="rounded-circle me-3"
                                    alt="employee photo" 
                                    width="35" 
                                    height="35"
                                />
                                <div>
                                    <div class="fw-medium">{{ $collaborator->full_name }}</div>
                                    <div class="text-muted fs-6">{{ $collaborator->account->email }}</div>    
                                </div>                                
                            </div>

                            <div class="ms-auto">
                                <div>
                                    <select 
                                        wire:model.change="collaborators.{{ $collaborator->employee_id }}" 
                                        wire:change="updateCollaboratorAccess({{ $collaborator->employee_id }})" 
                                        class="form-select"
                                        >
                                        <option class="text-center" value="viewer">{{ __('Viewer') }}</option>
                                        <option class="text-center" value="editor">{{ __('Editor') }}</option>
                                        <option class="text-center" value="removed">{{ __('Remove') }}</option>
                                    </select>
                                </div>                                
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </x-slot:content>
        <x-slot:footer>
        </x-slot:footer>
    </x-modals.dialog>

    <button 
        type="button" 
        class="px-3 py-2 rounded-5 btn btn-outline-primary fw-light" 
        onclick="openModal('{{ $modalId }}')"
    >
        <div class="d-flex text-end align-items-center">
            <i class="icon icon-large me-2" data-lucide="user-round-plus"></i>
            {{ __('Add Collaborators') }}
        </div>
    </button>
</div>

@script
<script>
    Livewire.on('addedNewCollaborators', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });

    Livewire.on('updatedCollaboratorAccess', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript