@props(['label', 'nonce'])

<label for="{{ $attributes->get('id') }}" class="mb-1">{{ $label }}</label>
<div class="input-group mb-3 position-relative">
    <div class="px-2 d-flex align-items-center position-absolute icon text-primary" wire:ignore
        nonce="{{ $nonce }}"><i data-lucide="mail"></i>
        @if (!empty($input_icon_left))
            {{ $input_icon_left }}
        @endif
    </div>
    <input type="email"
        {{ $attributes->merge([
            'class' => 'form-control  border-bottom ps-5',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
            'pattern' => $attributes->get('pattern', '/^[a-zA-Z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'),
        ]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" maxlength="320" required
        wire:model="{{ $attributes->get('name') }}" nonce="{{ $nonce }}">
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
