@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'buttonAttributes' => new ComponentAttributeBag(),
    'buttonContent' => null,
    'overrideClass' => false,
    'overrideButtonClass' => false,
])

@php
    $defaultAttributes = ['class' => 'modal-header border-0'];
    $defaultButtonAttributes = ['class' => 'btn-close p-1 p-md-3 mt-md-n4 me-md-n3'];

    if (!$overrideClass) {
        $attributes = $attributes->merge($defaultAttributes);
    }

    if (!$overrideButtonClass) {
        $buttonAttributes = $buttonAttributes->merge($defaultButtonAttributes);
    }
@endphp

<div {{ $attributes }}>
    {{ $slot }}
    <button type="button" class="btn-close p-1 p-md-3 mt-md-n4 me-md-n3" data-bs-dismiss="modal" aria-label="Close"
        {{ $buttonAttributes }}>
        {{ $buttonContent ?? '' }}
    </button>
</div>
