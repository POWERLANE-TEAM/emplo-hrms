@props(['input_id', 'label', 'nonce', 'input_name', 'auto_complete' => 'off'])

<label for="{{ $input_id }}" class="mb-1">{{ $label }}</label>
<div class="input-group mb-3 position-relative mt-3">
    <div class="px-2 d-flex align-items-center position-absolute icon text-primary" wire:ignore><i data-lucide="mail"></i>
    </div>
    <input type="email" id="{{ $input_id }}" name="{{ $input_name }}"
        pattern="/^[a-zA-Z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,4}$/" maxlength="191" required wire:model="{{ $input_name }}"
        autocomplete="{{ $auto_complete }}" {{ $attributes->merge(['class' => 'form-control  border-bottom ps-5']) }}>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
