@props([
    'modalId' => 'uploadPayslipModal',
])

<div>
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Upload Payslip') }}</h1>
            <button wire:click="$dispatchSelf('close-{{ $modalId }}')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            @if (! $loading)
                <div wire:loading.remove class="row mb-3">
                    <div class="col">
                        <form wire:submit="save" enctype="multipart/form-data">
                            <input type="file" name="file" wire:model="file" accept="application/pdf" />
                            @error('file')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror

                            <button @disabled(empty($file)) type="submit" class="btn btn-primary">
                                {{ __('Upload') }}
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div wire:loading class="">
                    @include('livewire.placeholder.payslip-upload')           
                </div>
            @endif
        </x-slot:content>
        <x-slot:footer>

        </x-slot:footer>
    </x-modals.dialog>
</div>

@script
<script>
    $wire.on('uploadPayslip', () => {
        openModal('{{ $modalId }}');
    });

    $wire.on('close-{{ $modalId }}', () => {
        hideModal('{{ $modalId }}');
        $wire.set('loading', true);
    });

    Livewire.on('payslipUploaded', (event) => {
        hideModal('{{ $modalId }}');
        $wire.set('loading', true)

        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript