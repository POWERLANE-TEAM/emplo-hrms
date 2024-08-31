@props(['no_crawl' => false, 'description' => '', 'font_weights' => [' 400 ', '500', '700']])

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

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-html.meta-seo :no_crawl="$no_crawl" :description="$description"></x-html.meta-seo>
    @php
        $nonce = csp_nonce();
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="csp-nonce" content="{{ $nonce }}">
    <?php Vite::useScriptTagAttributes(['onerror' => 'handleError(error)']); ?>
    @php
        Debugbar::getJavascriptRenderer()->setCspNonce($nonce);
    @endphp


    <script nonce="{{ csp_nonce() }}">
        console.time("DOMContentLoaded");
        console.time("loading");
        console.time("interactive");
        console.time("complete");
        document.addEventListener("DOMContentLoaded", () => {
            console.timeEnd("DOMContentLoaded");
        });

        document.addEventListener("readystatechange", (event) => {
            if (event.target.readyState === "loading") {

                console.timeEnd("loading");
            } else if (event.target.readyState === "interactive") {

                console.timeEnd("interactive");
            } else if (event.target.readyState === "complete") {

                console.timeEnd("complete");
            }
        });
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin="anonymous">
    {{-- <link rel="preload"
        href="https://fonts.bunny.net/css?family=figtree:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        as="style" type="text/css" /> --}}
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:{{ $fonts }}&display=swap"
        as="style" type="text/css" />
    <link href="https://fonts.bunny.net/css?family=figtree:{{ $fonts }}&display=swap" rel="stylesheet" />

    <!-- Styles -->
    {{-- <style nonce="{{ csp_nonce() }}">
        @import url(https://fonts.bunny.net/css?family=figtree:{{$fonts}});
    </style> --}}

    {{ $slot }}

    <!-- Scripts -->
    @vite(['vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'])

</head>
