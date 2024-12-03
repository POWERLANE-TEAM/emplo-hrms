@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'id',
    'src',
    'srcIsUrl' => false,
    'containerAttributes' => new ComponentAttributeBag(),
    'overrideClass' => false,
    'overrideContainerClass' => false,
])

@php

    $defaultAttributes = ['class' => 'rounded-3'];

    $defaultContainerAttributes = ['class' => 'flex-grow-1 border border-1 rounded-3'];

    if (!$overrideClass) {
        $attributes = $attributes->merge($defaultAttributes);
    }

    if (!$overrideContainerClass) {
        $containerAttributes = $containerAttributes->merge($defaultContainerAttributes);
    }
@endphp

<div {{ $containerAttributes }}>
    <div class="flex-grow-1 px-4 position-relative">
        <button type="button" aria-controls="iframe-{{ $id }}"
            class="btn text-dark shadow rounded-circle btn-full-screen"><i class="icon-medium"
                data-lucide="expand"></i></button>
    </div>
    <iframe id="iframe-{{ $id }}" name="{{ $id }}" src="{{ $srcIsUrl ? $src : Storage::url($src) }}"
        {{ $attributes->merge([
            'allowfullscreen' => $attributes->get('allowfullscreen', 'yes'),
            'height' => $attributes->get('height', '100.5%'),
            'width' => $attributes->get('width', '100%'),
            'frameborder' => $attributes->get('frameborder', '0'),
            'allowpaymentrequest' => $attributes->get('allowpaymentrequest', 'false'),
            'loading' => $attributes->get('loading', 'lazy'),
        ]) }}></iframe>
</div>
