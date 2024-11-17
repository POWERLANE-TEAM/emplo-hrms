@props([
    'type' => 'search',
])

@aware(['nonce'])

<form style="display: contents">

    @if ($type == 'search')
        <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} type="search" autocomplete="off"
            autocapitalize="off" {{ $attributes }} nonce="{{ $nonce }}">
    @elseif ($type == 'text')
        <input {{ $attributes->merge(['class' => 'col-12 rounded-pill search']) }} autocomplete="off" {{ $attributes }}
            nonce="{{ $nonce }}">
    @endif

</form>
