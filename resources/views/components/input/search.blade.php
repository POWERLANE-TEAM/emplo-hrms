@props([
    'type' => 'search',
])

@if ($type == 'search')
    <input class="col-12 rounded-pill " type="search" {{ $attributes }}
        aria-placeholder="{{ $attributes->get('placeholder') }}">
@elseif ($type == 'text')
    <input class="col-12 rounded-pill search" {{ $attributes }}>
@endif
