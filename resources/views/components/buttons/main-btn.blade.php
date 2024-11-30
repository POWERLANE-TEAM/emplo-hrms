{{--
* |--------------------------------------------------------------------------
* | Main Button: Submit, Cancel
* |
* | Note: The Enable/Disable of the button is passable.
* |--------------------------------------------------------------------------
--}}

@props([
    'label' => 'Submit', 
    'nonce' => '', 
    'id' => null, 
    'loading' => 
    'Processing...', 
    'disabled' => true, 
    'target' => null
])

<div class="bottom-0 pt-1 pb-md-3 z-3 w-100">
    <button wire:loading.attr="disabled" type="submit" nonce="{{ $nonce }}"
            id="{{ $id }}"
            {{ $attributes->merge(['class' => 'btn btn-primary btn-lg']) }}
            @if($disabled) disabled @endif>
        
        {{ $label }}

        <span wire:target="{{ $target }}" class="spinner-border spinner-border-sm text-light" aria-hidden="true" wire:loading></span>
        <span class="visually-hidden" role="status" wire:loading>{{ $loading }}</span>
    </button>
</div>
