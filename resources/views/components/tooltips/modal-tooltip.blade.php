{{-- resources/views/components/modal-tooltip.blade.php --}}
@props([
    'icon' => 'help-circle', 
    'color' => 'text-info',
    'modalId' => '', // The ID of the modal to open
])

<button 
    class="ms-1 btn btn-link p-0" 
    data-bs-toggle="modal" 
    data-bs-target="#{{ $modalId }}"
    aria-haspopup="dialog"
    aria-expanded="false"
>
    <i data-lucide="{{ $icon }}" class="icon icon-large {{ $color }} icon-opacity"></i>
</button>