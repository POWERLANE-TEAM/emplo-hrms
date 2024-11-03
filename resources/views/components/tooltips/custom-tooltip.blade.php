{{--
* |--------------------------------------------------------------------------
* | Regular, Custom Tooltip
* |--------------------------------------------------------------------------
--}}

@props([
    'title' => 'Tooltip message', 
    'icon' => 'help-circle', 
    'placement' => 'right', 
    'color' => 'text-info',
])

<span 
    data-bs-toggle="tooltip" 
    data-bs-placement="{{ $placement }}" 
    title="{{ $title }}"
    class="ms-1"
>
    <i data-lucide="{{ $icon }}" class="icon icon-large {{ $color }}"></i>
</span>
