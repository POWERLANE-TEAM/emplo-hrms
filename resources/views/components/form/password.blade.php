@props([
    'input_id',
    'label',
    'nonce',
    'input_name',
    'input_icon_left',
    'has_confirm' => false,
    'auto_complete' => 'off',
])

@isset($label)
    <label for="{{ $input_id }}" class="mb-1">{{ $label }}</label>
@endisset
<div class="input-group mb-3">
    <div class="px-2 d-flex position-absolute icon  text-primary" wire:ignore nonce="{{ $nonce }}">
        @if (!empty($input_icon_left))
            {{ $input_icon_left }}
        @endif
    </div>
    <input type="password" id="{{ $input_id }}"
        aria-owns="{{ $input_id }}-feedback {{ $has_confirm ? $input_id . '-confirm' : '' }}"
        name="{{ $input_name }}" pattern="^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[^\s]{8,72}$" minlength="8"
        maxlength="72" required wire:model="{{ $input_name }}" autocomplete="{{ $auto_complete }}"
        {{ $attributes->merge(['class' => 'form-control rm-bg-icon border-bottom ps-5 z-0']) }}>

    @if (!empty($toggle_password))
        {{ $toggle_password }}
    @endif
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
