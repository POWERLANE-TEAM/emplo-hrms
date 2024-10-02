@props(['label', 'nonce', 'input_icon_left'])

<label for="{{ $attributes->get('id') }}" class="mb-1">{{ $label }}</label>
<div class="input-group mb-3 position-relative ">
    <div class="px-2 d-flex align-items-center position-absolute icon text-primary" wire:ignore
        nonce="{{ $nonce }}">
        @if (!empty($input_icon_left))
            {{ $input_icon_left }}
        @endif
    </div>
    <input wire:model="{{ $attributes->get('name') }}"
        {{ $attributes->merge(['class' => 'form-control border-bottom ps-5', 'autocomplete' => $attributes->get('autocomplete', 'off')]) }}
        nonce="{{ $nonce }}">
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
