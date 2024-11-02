{{--
* |--------------------------------------------------------------------------
* | Main Button: Submit, Cancel
* |
* | Note: The Enable/Disable of the button is passable.
* |--------------------------------------------------------------------------
--}}


@props(['label' => 'Submit', 'nonce' => '', 'id' => null, 'loading' => 'Processing...', 'disabled' => true])

<div class="bottom-0 pt-1 pb-md-3 bg-body z-3 w-100">
    <button type="submit" nonce="{{ $nonce }}"
            id="{{ $id }}"
            @if($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif
            {{ $attributes->merge(['class' => 'btn btn-primary btn-lg']) }}
            @if($disabled) disabled @endif>
        
        {{ $label }}

        <span class="spinner-border spinner-border-sm text-light" aria-hidden="true" wire:loading></span>
        <span class="visually-hidden" role="status" wire:loading>{{ $loading }}</span>
    </button>
</div>
