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
        @livewireStyles(['nonce' => $nonce])
        @livewireScripts(['nonce' => $nonce])
        {{-- @livewireScriptConfig(['nonce' => $nonce]) --}}

        {{-- START: Critical Styles --}}
        {{-- Need to reduce Cumulative Layout Shift --}}
        <style nonce="{{ $nonce }}">
            section.top-vector {
                position: absolute;
                width: 100vw;
                height: 100vh;
                contain: layout;

                img.green-wave {
                    position: absolute;
                    min-width: 83svw;
                    width: 83svw;
                    height: auto;
                    top: -300px;
                    left: 30%;
                    transform: rotate(355deg);
                }

                svg.right-circle {
                    position: absolute;
                    right: calc(-96px + 1vw);
                    top: 60vh;
                    z-index: -10;
                }

                svg.left-circle {
                    position: absolute;
                    left: calc(-32px + 1vw);
                    top: 22vh;
                    z-index: -10;
                }
            }
        </style>

        {{-- END: Critical Styles --}}

        @yield('head')

    </x-html.head>

    <body class="">


        <section class="top-vector z-n1" draggable="false" ondragstart="return;">

            {{-- <div> --}}
            <picture>
                <source media="(min-width:2560px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xl.png') }}">
                <source media="(min-width:1200px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg.png') }}">
                <source media="(min-width:768px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.png') }}">
                <source media="(min-width:576px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-sm.png') }}">

                <img class="green-wave no-drag"
                    src="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.png') }}" width="600px"
                    height="300px" alt="">
            </picture>

            <svg class="left-circle" width="171" height="251" viewBox="0 0 171 251" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M156 125.5C156 186.527 106.527 236 45.5 236C-15.5275 236 -65 186.527 -65 125.5C-65 64.4725 -15.5275 15 45.5 15C106.527 15 156 64.4725 156 125.5Z"
                    stroke="#404040" stroke-opacity="0.05" stroke-width="30" />
            </svg>


            <svg class="right-circle " width="145" height="145" viewBox="0 0 145 145" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M137.5 72.5C137.5 108.399 108.399 137.5 72.5 137.5C36.6015 137.5 7.5 108.399 7.5 72.5C7.5 36.6015 36.6015 7.5 72.5 7.5C108.399 7.5 137.5 36.6015 137.5 72.5Z"
                    stroke="#61B000" stroke-width="15" />
            </svg>
            {{-- </div> --}}

        </section>

        <header class="top-nav sticky-md-top no-drag" draggable="false" ondragstart="return;">
            <nav class="d-flex justify-content-between align-items-center">
                <div class="d-flex site-name home ms-md-5">
                    <div class="align-content-center">
                        <div class="">
                            <x-html.pri-sm-logo></x-html.pri-sm-logo>
                        </div>
                    </div>

                    <x-nav-link href="/" :active="request()->is('/')" class="no-hover ps-0  nav-link">
                        <h1 class="fs-2  fw-bold text-primary">Powerlane</h1>
                    </x-nav-link>

                    @livewire('auth.google-one-tap')
                </div>
                <div class="d-flex align-items-center fw-bold">

                </div>
            </nav>
        </header>


        <main class="main container-fluid">

            @yield('content')

        </main>

        {{-- <x-html.test-elements></x-html.test-elements> --}}

        <x-guest.footer></x-guest.footer>

    </body>

</x-html>
