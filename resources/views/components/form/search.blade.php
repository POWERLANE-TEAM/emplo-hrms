@props([
    'type' => 'search',
])

@if ($type == 'search')
    <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} type="search" {{ $attributes }}>
@elseif ($type == 'text')
    <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} {{ $attributes }}>
@endif
