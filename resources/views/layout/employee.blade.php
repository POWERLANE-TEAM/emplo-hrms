<x-html>
    @php
        $font_array = [''];
    @endphp

    @isset($font_weights)
        @php
            $font_array = array_merge($font_weights, $font_array);
        @endphp
    @endisset

    <x-html.head description=" {{ $description ?? app()->name() }}" :font_weights="$font_array">

        @yield('head')

    </x-html.head>

    <body class=" ">
        <x-employee.nav.main-menu :sidebar_expanded="true" class="position-sticky top-0 start-0"></x-employee.nav.main-menu>
        <main class="main">

            @yield('content')

            <x-html.test-elements></x-html.test-elements>
        </main>

        <x-employee.footer></x-employee.footer>
    </body>
</x-html>
