@props([
    'type' => 'search',
])

@aware(['nonce'])

@if ($type == 'search')
    <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} type="search" {{ $attributes }}
        nonce="{{ $nonce }}">
@elseif ($type == 'text')
    <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} {{ $attributes }}
        nonce="{{ $nonce }}">
@endif
