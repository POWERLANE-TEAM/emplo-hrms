@props(['email' => ''])

<a {{ $attributes->merge(['class' => 'text-truncate']) }} title="{{ $email }}"
    href="mailto:{{ $email }}">{{ $slot }}{{ $email }}</a>
