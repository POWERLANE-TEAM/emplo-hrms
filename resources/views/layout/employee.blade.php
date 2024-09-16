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
        @pushOnce('lib-scripts')
            @stack('lib-scripts')
        @endPushOnce

        @pushOnce('lib-styles')
            @stack('lib-styles')
        @endPushOnce



    </x-html.head>

    <body class=" ">


        @stack('styles')
        @stack('scripts')

        <x-employee.nav.main-menu :sidebar_expanded="true" class="position-sticky top-0 start-0"></x-employee.nav.main-menu>
        <main class="main {{ $main_content_class ?? '' }}">

            @yield('content')

            {{-- <x-html.test-elements></x-html.test-elements> --}}
        </main>

        {{-- <x-employee.footer></x-employee.footer> --}}
        @livewireScripts()
    </body>
</x-html>
