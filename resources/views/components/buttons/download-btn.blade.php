{{--
* |--------------------------------------------------------------------------
* | Main Button: Submit, Cancel
* |
* | Note: The Enable/Disable of the button is passable.
* |--------------------------------------------------------------------------
--}}

@props([
    'slot' => 'Download',
    'nonce' => '',
    'loading' => 'Processing...',
    'target' => null,
])


<button type="button" nonce="{{ $nonce }}"
    {{ $attributes->merge(['class' => 'btn btn-lg', 'type' => $attributes->get('type', 'button')]) }}>
    <span class="ps-2 pe-3">
        <i class="icon icon-large me-2" style="transform: translateY(-5%)" data-lucide="download"></i>
        {{ $slot }}
    </span>

    <span wire:target="{{ $target }}" class="spinner-border spinner-border-sm text-light" aria-hidden="true"
        wire:loading></span>
    <span class="visually-hidden" role="status" wire:loading>{{ $loading }}</span>
</button>
