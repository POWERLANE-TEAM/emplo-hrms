@props([
    'modalId' => 'editOvertimeRequestModal'
])

<div>
    <x-modals.dialog data-bs-backdrop="static" data-bs-keyboard="false" :id="$modalId">
        <x-slot:title class="">
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Request Overtime') }}</h1>
            <button wire:click="$dispatch('resetOvertimeRequestModal')" wire:loading.attr="disabled" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        
        <x-slot:content>
            <div class="mb-3">
                <x-form.boxed-input-text name="state.workToPerform" id="work_performed" label="{{ __('Work To Perform') }}" :nonce="$nonce"
                    :required="true" placeholder="Detail of the work to be done" :readonly="! $editMode" />
                @error('state.workToPerform')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form.boxed-date name="state.date" id="date_ovt" label="{{ __('Date') }}" :nonce="$nonce" :required="true"
                        placeholder="Date of Overtime" :readonly="! $editMode" />
                    @error('state.date')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <x-form.boxed-time wire:model.live="state.startTime" id="hours_ot" label="{{ __('Start Time') }}" :nonce="$nonce" :required="true"
                    placeholder="Start Time" :readonly="! $editMode" />
                    @error('state.startTime')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <x-form.boxed-time wire:model.live="state.endTime" id="hours_ot" label="{{ __('End Time') }}" :nonce="$nonce" :required="true"
                    placeholder="End Time" :readonly="! $editMode" />
                    @error('state.endTime')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="fs-6 fw-medium text-secondary-emphasis">
                <div class="">{{ __("Requested Hours: {$hoursRequested}") }}</div>
                <div class="">{{ __("Date Filed: {$overtime?->filedAt}") }}</div>
            </div>

            <div class="list-group">
                @if ($overtime?->authorizedAt)
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

                @if ($overtime?->deniedAt)
                    <hr>
                    <div class="list-group-item border-0 ps-0 pt-0">
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
        </x-slot:content>
        <x-slot:footer>
            @if($editMode)
                <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                    <div class="me-auto">
                        <p class="fs-6 fw-medium mb-0">
                            <strong>Instructions: </strong>{{ __('Overtime submissions require a minimum of 30 minutes.') }}
                        </p>
                    </div>
                    <div class="ms-auto">
                        <button wire:click="update" wire:loading.attr="disabled" class="btn btn-primary">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                    <div class="me-auto">
                        <p class="fs-6 fw-medium mb-0">
                            <strong>Note: </strong>{{ __('Overtime requests cannot be updated after a week.') }}
                        </p>
                    </div>
                </div>
            @endif
        </x-slot:footer>                
    </x-modals.dialog>
</div>


@script
<script>
    $wire.on('showOvertimeRequest', (event) => {
        openModal('{{ $modalId }}');
    });

    $wire.on('resetOvertimeRequestModal', () => {
        hideModal('{{ $modalId }}')
    });

    Livewire.on('changesSaved', () => {
        hideModal('{{ $modalId }}');     
    })
</script>
@endscript