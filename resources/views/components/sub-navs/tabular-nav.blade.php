{{-- 
* |-------------------------------------------------------------------------- 
* | Tabular Navs
* |-------------------------------------------------------------------------- 
--}}

@props(['items' => [], 'guard' => 'employee'])

<div class="d-flex mb-3">
    @foreach ($items as $item)
        @if ($item['active'])
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