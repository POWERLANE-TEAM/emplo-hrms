@props(['active' => false])

<li class="breadcrumb-item {{ $active ? 'active' : '' }}">
    @if (!$active)
        <x-nav-link :active="$active" {{ $attributes }}>{{ $slot }}</x-nav-link>
    @else
        {{ $slot }}
    @endif
</li>
