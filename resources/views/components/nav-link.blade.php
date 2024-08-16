@props(['active' => false, 'type' => 'link'])

@if ($type == 'button')
    <button type="button" {{ $attributes->merge(['class' => $active ? 'active' : '']) }}>
        {{ $slot }}
    </button>
@elseif ($type == 'link')
    <a {{ $attributes->merge(['class' => $active ? 'active nav-link' : 'nav-link']) }}
        aria-current="{{ $active ? 'page' : 'false' }}">
        {{ $slot }}
    </a>
@endif
