@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'isHeading' => false,
    'heading',
    'containerAttributes' => new ComponentAttributeBag(),
    'overrideClass' => false,
    'overrideContainerClass' => false,
])

@php

    $defaultAttributes = ['class' => 'fs-2 fw-bold mb-2'];

    $defaultContainerAttributes = ['class' => 'ps-2 pt-2 pb-4 ms-n1'];

    if (!$overrideClass) {
        $attributes = $attributes->merge($defaultAttributes);
    }

    if (!$overrideContainerClass) {
        $containerAttributes = $containerAttributes->merge($defaultContainerAttributes);
    }
@endphp


<hgroup {{ $containerAttributes }}>
    <div {!! $attributes !!} {{ $isHeading ? 'role=heading aria-level=1' : '' }}>
        {{ $heading ?? '' }}</div>
    {{ $description ?? '' }}
</hgroup>
