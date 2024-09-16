@props(['active' => false, 'type' => 'link'])

@if ($type === 'button')
    <button type="button" {{ $attributes->merge(['class' => $active ? 'active ' : ' ']) }}>
        {{ $slot }}
    </button>
@elseif ($type === 'link')
    <a {{ $attributes->merge(['class' => $active && !Str::contains($attributes['class'], 'no-hover') ? 'active ' : ''])->when($active, fn($attrs) => $attrs->exceptProps(['href'])) }}
        href="{{ $active ? '#' : '' }}" aria-current="{{ $active ? 'page' : 'false' }}"
        {{ $active ? 'role="heading" aria-level="1"' : '' }}
        wire:navigate{{ $attributes->has('href') && ($attributes->get('href') !== '' && $attributes->get('href') !== '#' && $active && $attributes->has('data-preload')) ? '.hover' : '' }}>

        {{ $slot }}
    </a>
@endif
