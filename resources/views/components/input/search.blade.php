@props([
    'type' => 'search',
])

@if ($type == 'search')
    <input {{ $attributes->merge(['class' => 'col-12 rounded-pill ']) }} type="search" {{ $attributes }}
        aria-placeholder="{{ $attributes->get('placeholder') }}">
@elseif ($type == 'text')
    <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} {{ $attributes }}>
@endif
