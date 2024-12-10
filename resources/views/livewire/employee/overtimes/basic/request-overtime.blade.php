@props([
    'modalId' => 'requestOvertimeModal'
])

<div>
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5" id="{{ $modalId }}">{{ __('Request Overtime') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            <div class="mb-3">
                <x-form.boxed-input-text wire:model.blur="state.workToPerform" id="work_performed" label="{{ __('Work To Perform') }}" :nonce="$nonce"
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
                    <x-form.boxed-time name="state.startTime" id="hours_ot" label="{{ __('Start Time') }}" :nonce="$nonce" :required="true"
                    placeholder="Date of Overtime" />
                    @error('state.startTime')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <x-form.boxed-time name="state.endTime" id="hours_ot" label="{{ __('End Time') }}" :nonce="$nonce" :required="true"
                    placeholder="Date of Overtime" />
                    @error('state.endTime')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <p class="fs-6 fw-medium">
                <strong>Instructions: </strong>{{ __('Overtime submissions require a minimum of 30 minutes.') }}
            </p>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">{{ __('Submit Request') }}</button>
        </x-slot:footer>
    </x-modals.dialog>
</div>

@script
<script>
    Livewire.on('changes-saved', () => {
        hideModal('{{ $modalId }}');     
    })
</script>
@endscript
