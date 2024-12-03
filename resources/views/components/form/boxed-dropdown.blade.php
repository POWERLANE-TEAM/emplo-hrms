{{--
* |--------------------------------------------------------------------------
* | Boxed: Dropdown Input Field
* |--------------------------------------------------------------------------
{{--
* |--------------------------------------------------------------------------
* | Boxed: Dropdown Input Field
* |--------------------------------------------------------------------------
--}}

@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'label' => null,
    'options' => [],
    'nonce',
    'required' => false,
    'tooltip' => null,
    'overrideContainerClass' => false,
    'containerAttributes' => new ComponentAttributeBag(),
])

@php

    $defaultContainerAttributes = ['class' => 'input-group mb-3 position-relative'];

    if (!$overrideContainerClass) {
        $containerAttributes = $containerAttributes->merge($defaultContainerAttributes);
    }
@endphp

@if($label)
<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if ($required || $attributes->has('required'))
        <span class="text-danger">*</span>
    @endif

    {{-- Conditionally display the tooltip icon beside the label if tooltip and modalId are provided --}}
    @if ($tooltip && isset($tooltip['modalId']))
        <x-tooltips.modal-tooltip icon="help-circle" color="text-info" modalId="{{ $tooltip['modalId'] }}" class="ms-2" />
    @endif

</label>
<div {{ $containerAttributes }}>
    <!-- Dropdown input with boxed styling -->
    <select @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif
        {{ $attributes->merge([
            'class' => 'form-control form-select border ps-3 rounded pe-5',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
        ]) }}
        nonce="{{ $nonce }}">
        <option value="">{{ $attributes->get('placeholder', 'Select an option') }}</option>
        @foreach ($options as $value => $optionLabel)
            <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
