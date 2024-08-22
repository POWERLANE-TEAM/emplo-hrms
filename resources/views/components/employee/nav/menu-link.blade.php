@props(['active' => false, 'type' => 'link'])

<li>

    @if ($type == 'button')
        <button type="button" {{ $attributes->merge(['class' => $active ? 'active' : '']) }}>
            @if (!empty($icon))
                {{ $icon }}
            @endif
        </button>
    @elseif ($type == 'link')
        <a {{ $attributes->merge(['class' => $active && !Str::contains($attributes['class'], 'no-hover') ? 'active nav-link' : 'nav-link'])->when($active, fn($attrs) => $attrs->exceptProps(['href'])) }}
            aria-current="{{ $active ? 'page' : 'false' }}">
            @if (!empty($icon))
                {{ $icon }}
            @endif
        </a>
    @endif

</li>
