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
            <div class="mt-3 bg-body-secondary dropdown position-relative">
                <div class="d-flex align-items-center text-start fw-light text-secondary-emphasis btn bg-body border-dark-subtle w-100 p-2">
                    <i class="icon icon-large ms-2 me-2" data-lucide="search"></i>
                    <input
                        class="flex-grow-1 bg-body-secondary py-1"
                        type="search"
                        wire:model.live.debounce.250ms="searchQuery"
                        placeholder="{{ __('Search for collaborators') }}"
                    >
                </div>

                <div wire:loading wire:target="searchQuery">
                    @include('livewire.placeholder.collaborator')
                </div>

                @if (strlen($searchQuery) > 0)
                    <div wire:loading.remove wire:target="searchQuery" class="w-100 p-0 position-absolute border border-top-0 rounded-1 bg-body-secondary" x-transition style="z-index: 1050;">
                        <div class="overflow-auto" style="max-height: 350px;">
                            <ul class="list-unstyled mb-0 my-3" role="menu">                          
                                @foreach ($searchQueryResult as $employee)
                                    <li 
                                        x-data="{ showDropdown: false }"
                                        @click.stop="showDropdown = !showDropdown"
                                        @mouseenter="showDropdown = true"
                                        @mouseleave="showDropdown = false"
                                        wire:dirty.class="border  border-warning-subtle"
                                        wire:target="collaborators.{{ $employee->employee_id }}"
                                        class="dropdown-item py-2 px-3" 
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

                                @if (sizeof($searchQueryResult) > 0)
                                    <div class="border-top bg-body-secondary mt-3 p-3 position-sticky bottom-0">
                                        <div class="d-flex align-items-center justify-content-between">
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
                                    </div>
                                @else
                                    <div wire:loading.remove wire:target="searchQuery" class="my-5 d-flex justify-content-center align-items-center">
                                        <div class="fs-5 fw-medium">
                                            {{ __('No employee found named ') }}<strong>{{ '"'.$searchQuery.'"' }}</strong>
                                        </div>
                                    </div>                                    
                                @endif
                            </ul>                        
                        </div>
                    </div>                    
                @endif  
            </div>

            <div class="fw-medium mb-2 mt-3 position-sticky top-0">
                {{ __('People with access') }}
            </div>
            
            <div class="overflow-auto" style="max-height: 300px;">
                <ul class="p-1 list-unstyled w-100">
                    @foreach ($this->incidentCollaborators as $collaborator)
                        @if ($loop->index === 1)
                            @foreach ($collaborator as $authority)
                                <li class="py-2" wire:key="{{ $authority->user_id }}">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="text-decoration-none text-body" 
                                            href="{{ route("{$this->routePrefix}.employees.information", ['employee' => $authority->account->employee_id]) }}">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $authority->photo }}" 
                                                    class="rounded-circle me-3"
                                                    alt="employee photo" 
                                                    width="35" 
                                                    height="35"
                                                />
                                                <div>
                                                    <div class="fw-medium">{{ $authority->account->full_name }}</div>
                                                    <div class="text-muted fs-6">{{ $authority->email }}</div>    
                                                </div>                                
                                            </div>                                            
                                        </a>
        
                                        <div class="ms-auto">
                                            <div>
                                                <div class="w-100 text-start text-info">{{ __('Higher Authority') }}</div>
                                            </div>                                
                                        </div>
                                    </div>
                                </li> 
                            @endforeach
                        @else
                            <li class="py-2" wire:key="{{ $collaborator->employee_id }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a class="text-decoration-none text-body" 
                                    href="{{ route("{$this->routePrefix}.employees.information", ['employee' => $collaborator->employee_id]) }}">
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
                                    </a>

                                    <div class="ms-auto">
                                        <div>
                                            @if ($loop->first)
                                                <div class="w-100 text-start text-info">{{ __('Reporter') }}</div>
                                            @else
                                                <select
                                                    wire:change="updateCollaboratorAccess({{ $collaborator->employee_id }}, $event.target.value)" 
                                                    class="form-select"
                                                >
                                                    <option class="text-center" value="viewer" @if (! $collaborator->access->is_editor) selected @endif>{{ __('View Only') }}</option>
                                                    <option class="text-center" value="editor" @if ($collaborator->access->is_editor) selected @endif>{{ __('Editor') }}</option>
                                                    <option class="text-center" value="remove">{{ __('Remove') }}</option>
                                                </select>
                                            @endif
                                        </div>                                
                                    </div>
                                </div>
                            </li>  
                        @endif                          
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
            {{ __('Manage Collaborators') }}
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