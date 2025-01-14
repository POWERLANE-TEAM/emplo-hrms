{{--
* |--------------------------------------------------------------------------
* | Boxed: Input Field
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
    {{-- Conditionally display the red asterisk for required fields --}}
    @if ($attributes->has('required'))
        <span class="text-danger">*</span>
    @endif
    @if ($attributes->has('showPlaceholderOnLabel'))
    <span class="text-muted label-placeholder d-none">{!! $attributes->get('placeholder') !!}</span>
    @php $attributes = $attributes->except('showPlaceholderOnLabel'); @endphp
@endif
</label>
<div {{ $containerAttributes }}>
    <!-- Input with boxed styling -->
    <input @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif
        {{ $attributes->merge([
            'type' => $attributes->get('type', 'text'),
            'class' => 'form-control border ps-3 rounded',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
            'placeholder' => $attributes->get('placeholder', ''),
        ]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" nonce="{{ $nonce }}">
    </div>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
