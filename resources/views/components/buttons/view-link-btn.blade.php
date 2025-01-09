{{-- 
* |-------------------------------------------------------------------------- 
* | View Link Component 
* |-------------------------------------------------------------------------- 
--}}

@props([
    'link' => '#', // Default to '#' if no link is provided
    'text' => 'View All', // Default text
])

<div class="d-flex justify-content-end">
    <a href="{{ $link }}" {{ $attributes->merge(['class' => 'text-decoration-underline hover-opacity']) }}>
        {{ $text }}
    </a>
</div>
