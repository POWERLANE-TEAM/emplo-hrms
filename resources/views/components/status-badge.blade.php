@props(['color'])

<span {{ $attributes->merge() }}
    class="badge bg-{{ $color }}-subtle text-{{ $color }} text-uppercase">{{ $slot }}</span>
