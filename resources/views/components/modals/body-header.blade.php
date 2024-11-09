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
    $defaultAttributes = ['class' => 'mx-auto fw-medium'];
    $defaultHeaderAttributes = ['class' => 'fs-2 fw-bold text-center text-primary mb-md-3'];
    $defaultMessageAttributes = ['class' => 'fs-5 mb-md-4'];

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
