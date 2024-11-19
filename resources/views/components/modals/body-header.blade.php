@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'header',
    'message',
    'headerAttributes' => new ComponentAttributeBag(),
    'messageAttributes' => new ComponentAttributeBag(),
    'overrideClass' => false,
    'overrideHeaderClass' => false,
    'overrideMessageClass' => false,
])

@php
    $defaultAttributes = ['class' => 'mx-auto fw-medium mb-md-4'];
    $defaultHeaderAttributes = ['class' => 'fs-1 fw-bold text-center text-primary mb-3 mb-md-1'];
    $defaultMessageAttributes = ['class' => 'fs-4 mb-3 mb-md-1'];

    if (!$overrideClass) {
        $attributes = $attributes->merge($defaultAttributes);
    }

    if (!$overrideHeaderClass) {
        $headerAttributes = $headerAttributes->merge($defaultHeaderAttributes);
    }

    if (!$overrideMessageClass) {
        $messageAttributes = $messageAttributes->merge($defaultMessageAttributes);
    }
@endphp

<hgroup {{ $attributes }}>
    <div {{ $headerAttributes }}>
        {{ $header }}
    </div>
    <div {{ $messageAttributes }}>
        {{ $message }}
    </div>
</hgroup>
