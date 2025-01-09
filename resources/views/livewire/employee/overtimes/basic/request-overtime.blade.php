@props([
    'modalId' => 'requestOvertimeModal'
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
                    :required="true" placeholder="Detail of the work to be done" />
                @error('state.workToPerform')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form.boxed-date name="state.date" id="date_ovt" label="{{ __('Date') }}" :nonce="$nonce" :required="true"
                        placeholder="Date of Overtime" />
                    @error('state.date')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <x-form.boxed-time wire:model.live="state.startTime" id="hours_ot" label="{{ __('Start Time') }}" :nonce="$nonce" :required="true"
                    placeholder="Start Time" />
                    @error('state.startTime')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <x-form.boxed-time wire:model.live="state.endTime" id="hours_ot" label="{{ __('End Time') }}" :nonce="$nonce" :required="true"
                    placeholder="End Time" />
                    @error('state.endTime')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <p class="fs-6 fw-medium text-secondary-emphasis">
                <strong>{{ __('Requested Hours: ') }}</strong>{{ $hoursRequested }}
            </p>
        </x-slot:content>
        
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                <div class="me-auto">
                    <p class="fs-6 fw-medium mb-0">
                        <strong>Instructions: </strong>{{ __('Overtime submissions require a minimum of 30 minutes.') }}
                    </p>
                </div>
                <div class="ms-auto">
                    <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">
                        {{ __('Submit Request') }}
                    </button>
                </div>
            </div>
        </x-slot:footer>                
    </x-modals.dialog>
</div>


@script
<script>
    $wire.on('resetOvertimeRequestModal', () => {
        hideModal('{{ $modalId }}')
    });

    Livewire.on('changesSaved', () => {
        hideModal('{{ $modalId }}');     
    })
</script>
@endscript
