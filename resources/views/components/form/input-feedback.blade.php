@use ('Illuminate\View\ComponentAttributeBag')

@aware(['nonce'])

@props([
    'nonce' => csp_nonce(),
    'overrideClass' => false,
    'attributes' => new ComponentAttributeBag(),
])

@php

    $defaultAttributes = ['class' => 'invalid-feedback'];

    if (!$overrideClass) {
        $attributes = $attributes->merge($defaultAttributes);
    }
@endphp

<div {{ $attributes }} role="alert" id="{{ $feedback_id }}" nonce="{{ $nonce }}">
    {{ $message }}
</div>
