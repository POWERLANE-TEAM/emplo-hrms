@props([
    'modalId' => 'uploadPayslipModal',
])

<div>
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Upload ') }}</h1>
            <button wire:click="$dispatchSelf('close-{{ $modalId }}')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>

        </x-slot:content>
        <x-slot:footer>

        </x-slot:footer>
    </x-modals.dialog>
</div>

@script
<script>
    $wire.on('uploadPayslip', (event) => {
        // alert('hello');
        console.log(event);
        // openModal('{{ $modalId }}');
    });
</script>
@endscript