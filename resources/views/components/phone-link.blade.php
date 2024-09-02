@props(['phone' => [], 'separator' => ', '])

@props(['phone' => '', 'separator' => ', '])

@if (is_array($phone))
    @foreach ($phone as $number)
        <a {{ $attributes->merge(['class' => '']) }} title="{{ $number }}"
            href="tel:{{ $number }}">{{ $slot }}{{ $number }}</a>
        @if (!$loop->last)
            {{ $separator }}
        @endif
    @endforeach
@elseif($phone)
    <a {{ $attributes->merge(['class' => '']) }} title="{{ $phone }}"
        href="tel:{{ $phone }}">{{ $slot }}{{ $phone }}</a>
@endif
