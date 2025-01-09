@props(['label', 'nonce', 'boxed' => false])

@php
    $placeholderDigits = null;
    if (!$attributes->has('placeholder')) {
        $placeholderDigits = '27' . fake()->numerify('#######');
    }
    $placeholder = $attributes->get(
        'placeholder',
        'e.g. ' . (config('app.region_mode') === 'local' ? '09' : '639') . $placeholderDigits,
    );
    $inputPattern = config('app.region_mode') === 'local' ? '^\d{11}$' : '^\d{8,15}$';
@endphp

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if ($attributes->has('required'))
        <span class="text-danger">*</span>
    @endif
</label>
<div class="input-group mb-3 position-relative">
    @if (!empty($input_icon_left))
        <div class="px-2 d-flex align-items-center position-absolute icon text-primary" wire:ignore
            nonce="{{ $nonce }}">
            {{ $input_icon_left }}
        </div>
    @endif
    <input type="tel"
        {{ $attributes->merge([
            'class' =>
                'form-control' . ($boxed ? ' border rounded ' : ' border-bottom ') . (isset($input_icon_left) ? ' ps-5' : ''),
            'autocomplete' => $attributes->get('autocomplete', 'tel'),
            'pattern' => $attributes->get('pattern', $inputPattern),
            'placeholder' => $placeholder,
        ]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" maxlength="15" required
        @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif nonce="{{ $nonce }}">
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
