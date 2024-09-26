@props(['input_id', 'label', 'nonce', 'input_name', 'auto_complete' => 'off'])

<label for="{{ $input_id }}" class="mb-1">{{ $label }}</label>
<div class="input-group mb-3 position-relative mt-3">
    <div class="px-2 d-flex align-items-center position-absolute icon text-primary" wire:ignore
        nonce="{{ $nonce }}"><i data-lucide="mail"></i>
        @if (!empty($input_icon_left))
            {{ $input_icon_left }}
        @endif
    </div>
    <input type="email" id="{{ $input_id }}" name="{{ $input_name }}" nonce="{{ $nonce }}"
        pattern="/^[a-zA-Z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,4}$/" maxlength="320" required
        wire:model.blur="{{ $input_name }}" autocomplete="{{ $auto_complete }}"
        {{ $attributes->merge(['class' => 'form-control  border-bottom ps-5']) }}>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
