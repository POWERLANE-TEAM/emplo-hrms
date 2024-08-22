<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-html.meta-seo></x-html.meta-seo>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="csp-nonce" content="{{ csp_nonce() }}">

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
    {{-- Kindly update fonts needed   --}}
    {{-- by removing unnecessary font size improves performance from 74 -> 80 --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    {{-- <link rel="preload"
        href="https://fonts.bunny.net/css?family=figtree:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        as="style" type="text/css" /> --}}
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,400i,500,500i,700,700i&display=swap"
        as="style" type="text/css" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,400i,500,500i,700,700i&display=swap" rel="stylesheet" />

    <!-- Styles -->
    {{-- <style nonce="{{ csp_nonce() }}">
        @import url(https://fonts.bunny.net/css?family=figtree:400,400i,500,500i,700,700i);
    </style> --}}

    {{ $slot }}

    <!-- Scripts -->
    @vite(['vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'])

</head>
