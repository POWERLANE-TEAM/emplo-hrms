@props(['label', 'nonce', 'input_icon_left'])

<label for="{{ $attributes->get('id') }}" class="mb-1">{{ $label }}</label>
<div class="input-group mb-3 position-relative ">
    <div class="px-3 d-flex align-items-center position-absolute icon text-primary" wire:ignore
        nonce="{{ $nonce }}">
        @if (!empty($input_icon_left))
            {{ $input_icon_left }}
        @endif
    </div>
    <input @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif
        {{ $attributes->merge(['class' => 'form-control border-bottom ps-5', 'autocomplete' => $attributes->get('autocomplete', 'off')]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" nonce="{{ $nonce }}">
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
