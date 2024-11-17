@props(['color' => null, 'randomize' => false])

@php
    // Randomize colors.
    $colors = ['green', 'blue', 'teal', 'purple'];

    if ($randomize && !$color) {
        $color = $colors[array_rand($colors)];
    }
@endphp

<span {{ $attributes->merge() }}
    class="badge bg-{{ $color }}-subtle text-{{ $color }} text-uppercase">
    {{ $slot }}
</span>
