@props(['active' => false, 'type' => 'link'])

@if ($type === 'button')
    <button type="button" {{ $attributes->merge(['class' => $active ? 'active ' : ' ']) }}>
        {{ $slot }}
    </button>
@elseif ($type === 'link')
    <a {{ $attributes->merge(['class' => $active && !Str::contains($attributes['class'], 'no-hover') ? 'active ' : ''])->when($active, fn($attrs) => $attrs->exceptProps(['href'])) }}
        aria-current="{{ $active ? 'page' : 'false' }}">
        {{ $slot }}
    </a>
@endif
