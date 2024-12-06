{{--
* |--------------------------------------------------------------------------
* | Tabular Navs
* |--------------------------------------------------------------------------
--}}
@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'items' => [],
    'guard' => 'employee',
    'containerAttributes' => new ComponentAttributeBag(),
    'overrideContainerClass' => false,
    'isActiveClosure' => null,
])

@php

    $defaultContainerAttributes = ['class' => 'd-flex mb-3'];

    if (!$overrideContainerClass) {
        $containerAttributes = $containerAttributes->merge($defaultContainerAttributes);
    }
@endphp

<div {{ $containerAttributes }}>
    @foreach ($items as $item)
        @php
            // Determine if the current route matches the item's route
$isActive = request()->routeIs($routePrefix . '.' . $item['route']);

            // Optional additional check for active state
            if ($isActive && $isActiveClosure instanceof \Closure) {
                $isActive = $isActiveClosure($isActive, $item);
            }
        @endphp

        @if ($isActive)
            <span class="fw-bold underline-padded text-primary me-4 mb-0">
                {{ $item['title'] }}
            </span>
        @else
            <a wire:navigate href="{{ route($guard . '.' . $item['route'], $item['routeParams'] ?? null) }}"
                class="fw-light text-muted text-decoration-none me-4 mb-0">
                {{ $item['title'] }}
            </a>
        @endif
    @endforeach
</div>
