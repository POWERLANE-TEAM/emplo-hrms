@props(['font_weights'])

@isset($font_weights)
    @php
        $allowed_fonts = [
            '300',
            '300i',
            '400',
            '400i',
            '500',
            '500i',
            '600',
            '600i',
            '700',
            '700i',
            '800',
            '800i',
            '900',
            '900i',
        ];

        $font_array = array_merge($font_weights, ['400', '500', '700']);
        $trimmed_fonts = array_map('trim', $font_array);
        $unique_fonts = array_unique($trimmed_fonts);

        $filtered_fonts = array_intersect($unique_fonts, $allowed_fonts);
        $fonts = implode(',', $filtered_fonts);
        $fonts = trim($fonts, ', ');
    @endphp
@endisset

<link rel="preconnect" href="https://fonts.bunny.net" crossorigin="anonymous">
<link rel="preload" href="https://fonts.bunny.net/css?family=figtree:{{ $fonts }}&display=swap" as="style"
    type="text/css" />
<link href="https://fonts.bunny.net/css?family=figtree:{{ $fonts }}&display=swap" rel="stylesheet" />
