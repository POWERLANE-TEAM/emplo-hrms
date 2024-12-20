@use('Illuminate\Support\Carbon')

@props([
    'modalId' => 'requestOvertimeApprovalModal',
    'collapsibleId' => 'denyOtRequestCollapse',
])

<div>
    <style>
        .left-col {
            display: grid;
            grid-template-columns: 130px 1fr;
            column-gap: 20px;
            row-gap:5px;
        }
    
        .right-col {
            display: grid;
            grid-template-columns: max-content 1fr;
            column-gap: 20px;
            row-gap: 5px;
        }
    </style>

    <x-modals.dialog data-bs-backdrop="static" data-bs-keyboard="false" :id="$modalId">
        <x-slot:title class="">
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Overtime Request Information') }}</h1>
            <button wire:click="$dispatch('closeOvertimeRequestModal')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            @if (! $loading)
                <div class="row mb-3">
                    <div class="col-5">
                        <div class="d-flex flex-column align-items-center text-secondary-emphasis">
                            <div class="text-center">
                                <img 
                                    src="{{ $overtime?->requestorPhoto }}" 
                                    alt="Employee photo" 
                                    class="rounded-circle mb-2"
                                    height="70"
                                    width="70" 
                                    style="object-fit: cover;"
                                />
                                <div class="fw-semibold fs-5">
                                    {{ $overtime?->requestorName }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ $overtime?->requestorJobTitle }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "Level {$overtime?->requestorJobLevel}: {$overtime?->requestorJobLevelName}" }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "Employee ID: {$overtime?->requestorId}" }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ $overtime?->requestorEmploymentStatus }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "{$overtime?->requestorShift} ({$overtime?->requestorShiftSched}) "}}
                                </div>        
                            </div>
                        </div>
                    </div>
                
                    <div class="col-7 mt-3 mt-md-0 text-secondary-emphasis">
                        <div class="mb-3">
                            <label for="work_performed" class="fw-medium form-label">{{ __('Work To Perform') }}</label>
                            <div class="form-control bg-body-secondary p-2 rounded-3" id="work_performed">
                                {{ $overtime?->workToPerform }}
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_ovt" class="fw-medium form-label">{{ __('Requested Date') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="date_ovt">
                                    {{ $overtime?->date }}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="hours_ot" class="fw-medium form-label">{{ __('Start Time') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="hours_ot">
                                    {{ $overtime?->startTime }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="hours_ot_end" class="fw-medium form-label">{{ __('End Time') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="hours_ot_end">
                                    {{ $overtime?->endTime }}
                                </div>
                            </div>
                        </div>

                        <div class="right-col fs-6 fw-medium">
                            <div class="">{{ __('Requested Hours: ') }}</div>
                            <div>{{ $overtime?->hrsRequested }}</div>
                            <div class="">{{ __('Date Filed: ') }}</div>
                            <div>{{ $overtime?->filedAt }}</div>
                        </div>

                        <div class="list-group">
                            @if ($overtime->authorizedAt)
                                <hr>
                                <div class="list-group-item border-0 ps-0">
                                    <h6 class="fw-semibold text-primary">{{ __('Authorized') }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary-emphasis">{{ __('by: ') }}
                                            <span class="fw-semibold">
                                                {{ $overtime?->authorizedBy }}
                                            </span>
                                        </span>
                                        <span class="text-muted small">{{ $overtime?->authorizedAt }}</span>
                                    </div>
                                </div>
                            @endif
            
                            @if ($overtime->deniedAt)
                                <hr>
                                <div class="list-group-item border-0 ps-0">
                                    <h6 class="fw-semibold text-danger">{{ __('Request Denied') }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary-emphasis">{{ __('by: ') }}
                                            <span class="fw-semibold">
                                                {{ $overtime?->deniedBy }}
                                            </span>
                                        </span>
                                        <span class="text-muted small">{{ $overtime?->deniedAt }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <label for="feedback" class="form-label text-muted">{{ __('Reason:') }}</label>
                                        <textarea id="feedback" class="form-control bg-body-secondary" rows="3" readonly>{{ $overtime?->feedback }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>                         
                </div>
            @else
                <div wire:loading class="col-12 my-4">
                    @include('livewire.placeholder.overtime-request-content')
                </div>  
            @endif                
            <div wire:ignore.self class="mb-3 mt-5 px-3 collapse" id="{{ $collapsibleId }}">
                <div class="">
                    <label for="textarea-id" class="mb-1 fw-medium mb-3">
                        {{ __("Why? Tell {$overtime?->requestorFirstName} here.") }}
                        <span class="text-danger" style="display: none;">*</span>
                    </label>
                    <div class="input-group mb-3 position-relative">
                        <textarea
                            id="denyFeedback" 
                            wire:model="feedback" 
                            class="form-control border ps-3 rounded" 
                            autocomplete="off" 
                            placeholder="{{ __('Enter your reason here') }}"
                            aria-owns="denyFeedback"
                            rows=4
                            @error('feedback') is-invalid @enderror
                        >
                        </textarea>
                        @error('feedback')
                            <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                    <div class="ms-auto">
                        <button 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#{{ $collapsibleId }}" 
                            aria-expanded="false" 
                            aria-controls="collapseControls" 
                            class="btn btn-secondary me-2 px-4"
                            wire:loading.attr="disabled"
                            wire:target="approveOtRequest, denyOtRequest">
                            {{ __('Cancel') }}
                        </button>
                        <button 
                            type="button" 
                            wire:click="denyOtRequest" 
                            class="btn btn-danger px-4"
                            wire:loading.attr="disabled"
                            wire:target="approveOtRequest, denyOtRequest">
                            {{ __('Confirm Deny') }}
                        </button>
                    </div>
                </div>
            </div>                
        </x-slot:content>        
        <x-slot:footer>
            <div wire:ignore.self id="footer">
                @if (! $loading && ! $isReadOnly)
                    <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                        <div class="ms-auto">
                            <button 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#{{ $collapsibleId }}" 
                                aria-expanded="false" 
                                aria-controls="collapseControls" 
                                class="btn btn-danger me-2 px-4"
                                wire:loading.attr="disabled"
                                wire:target="approveOtRequest, denyOtRequest">
                                {{ __('Deny') }}
                            </button>
                            <button 
                                type="button" 
                                wire:click="approveOtRequest" 
                                class="btn btn-primary px-4"
                                wire:loading.attr="disabled"
                                wire:target="approveOtRequest, denyOtRequest">
                                {{ __('Approve') }}
                            </button>
                        </div>
                    </div>                
                @endif                
            </div>                
        </x-slot:footer>                
    </x-modals.dialog>
</div>

@script
<script>
    const collapseId = document.getElementById('{{ $collapsibleId }}');
    const collapsible = bootstrap.Collapse.getOrCreateInstance(collapseId);
    const footer = document.getElementById('footer');
    const modalId = document.getElementById('{{ $modalId }}');

    $wire.on('showOvertimeRequestApproval', () => {
        collapsible.hide();
        openModal('{{ $modalId }}');
    });

    Livewire.on('changesSaved', () => {
        hideModal('{{ $modalId }}');     
    })

    $wire.on('closeOvertimeRequestModal', () => {
        hideModal('{{ $modalId }}')
    })

    modalId.addEventListener('hidden.bs.modal', () => {
        $wire.set('loading', true);
    });

    if (collapsible && footer) {
        collapseId.addEventListener('show.bs.collapse', () => {
            footer.style.display = 'none';
        });

        collapseId.addEventListener('hidden.bs.collapse', () => {
            footer.style.display = 'flex';
        });
    }
</script>
@endscript
