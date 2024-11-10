{{-- 
* |-------------------------------------------------------------------------- 
* | Callouts
* |
* | Note: Implementation examples can be seen in html/test-callouts
* |-------------------------------------------------------------------------- 
--}}

@props(['type' => 'info', 'description' => null, 'note' => false])

@php
    // Define class, icon, and color mappings based on the type
    $config = [
        'success' => ['class' => 'callout-success', 'icon' => 'badge-check', 'color' => 'text-success'],
        'warning' => ['class' => 'callout-warning', 'icon' => 'triangle-alert', 'color' => 'text-warning'],
        'error' => ['class' => 'callout-danger', 'icon' => 'message-circle-x', 'color' => 'text-danger'],
        'info' => ['class' => 'callout-info', 'icon' => 'info', 'color' => 'text-info'],
    ];

    // Get the style, icon, and color based on the provided type, or default to 'info'
    $styleClass = $config[$type]['class'] ?? $config['info']['class'];
    $icon = $config[$type]['icon'] ?? $config['info']['icon'];
    $iconColor = $config[$type]['color'] ?? $config['info']['color'];
@endphp

<div class="px-3 mb-4">
    <div class="callout {{ $styleClass }} bg-body-tertiary d-flex align-items-center">
        <i class="icon p-1 mx-2 {{ $iconColor }}" data-lucide="{{ $icon }}"></i>

        {{-- Display the description --}}
        <span>
        @if($note)
            <strong>NOTE: </strong>
        @endif
        {{ $description ?? 'Default description text if none provided.' }}</span>
    </div>
</div>

