{{--
* |--------------------------------------------------------------------------
* | Boxed: Time Input Field
* |--------------------------------------------------------------------------
--}}
@use ('Illuminate\View\ComponentAttributeBag')

@props(['label', 'nonce', 'overrideContainerClass' => false, 'containerAttributes' => new ComponentAttributeBag()])

@php
    $defaultContainerAttributes = ['class' => 'input-group mb-3 position-relative'];

    if (!$overrideContainerClass) {
        $containerAttributes = $containerAttributes->merge($defaultContainerAttributes);
    }
@endphp

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk if the required attribute is present --}}
    @if ($attributes->has('required'))
        <span class="text-danger">*</span>
    @endif
</label>
<div {{ $containerAttributes }}>
    <input type="time" @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif
        {{ $attributes->merge([
            'class' => 'form-control border ps-3 rounded',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
            'placeholder' => $attributes->get('placeholder', 'HH:mm'),
            {{-- Placeholder with time format --}}
        ]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" nonce="{{ $nonce }}">
    </div>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
