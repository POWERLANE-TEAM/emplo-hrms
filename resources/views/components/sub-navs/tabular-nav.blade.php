{{-- 
* |-------------------------------------------------------------------------- 
* | Tabular Navs
* |-------------------------------------------------------------------------- 
--}}

@props(['items' => [], 'guard' => 'employee'])

<div class="d-flex mb-3">
    @foreach ($items as $item)
        @php
            // Determine if the current route matches the item's route
            $isActive = request()->routeIs($guard . '.' . $item['route']);
        @endphp

        @if ($isActive)
            <span class="fw-bold underline-padded text-primary me-4 mb-0">
                {{ $item['title'] }}
            </span>
        @else
            <a href="{{ route($guard . '.' . $item['route']) }}" class="fw-light text-muted text-decoration-none me-4 mb-0">
                {{ $item['title'] }}
            </a>
        @endif
    @endforeach
</div>
