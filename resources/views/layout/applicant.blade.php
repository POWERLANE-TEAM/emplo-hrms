@props(['icon_size' => '25', 'icon_ratio' => '1/1'])
<x-html>

    @php
        $font_array = ['800'];
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

        {{-- @assets --}}
        <style nonce="{{ $nonce }}">

        </style>
        {{-- @endassets --}}

        {{-- END: Critical Styles --}}

        @yield('head')
    </x-html.head>

    <body class="">

        <header class="top-nav sticky-md-top bg-primary">
            <nav class="d-flex justify-content-between align-items-center">
                <div class="d-flex site-name home ms-md-5">
                    <div class="align-content-center">
                        <div class="">
                            <x-html.pri-sm-logo></x-html.pri-sm-logo>
                        </div>
                    </div>

                    <x-nav-link href="/" :active="request()->is('/')" class="no-hover ps-0 fw-semibold nav-link">
                        <h1 class="fs-2 fw-bolder text-white">Powerlane</h1>
                    </x-nav-link>
                </div>
                <div class="d-flex align-items-center fw-bold ">
                    <section class="desktop-topnav d-none d-md-flex">
                        <aside>
                            <div class="dropdown ">
                                <button id="theme-toggle-btn" class="bg-transparent border-0 dropdown-toggle"
                                    type="button" aria-label="Toggle light/dark theme" data-bs-toggle="dropdown">
                                    <picture class="">
                                        <source media="(min-width:2560px)" class=""
                                            srcset="{{ Vite::asset('resources/images/icons/moon-and-stars-69x69.webp') }}">
                                        <source media="(min-width:768px)" class=""
                                            srcset="{{ Vite::asset('resources/images/icons/moon-and-stars-69x69.webp') }}">

                                        <img class="icon" width="{{ $icon_size }}" height="{{ $icon_size }}"
                                            aspect-ratio="{{ $icon_ratio }}"
                                            src="{{ Vite::asset('resources/images/icons/moon-and-stars-35x35.webp') }}"
                                            alt="">
                                    </picture>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><span class="dropdown-item" data-isSystem="true">System</span></li>
                                    <li><span class="dropdown-item" data-isSystem="false">Light</span></li>
                                    <li><span class="dropdown-item" data-isSystem="false">Dark</span></li>
                                </ul>
                            </div>
                        </aside>

                        <x-notif-dropdown>
                            <li class="dropdown-item">Notif 1</li>
                            <li class="dropdown-item">Notif 1</li>
                            <li class="dropdown-item">Notif 1</li>
                        </x-notif-dropdown>

                        <div class="dropdown user-menu">
                            <button class=" bg-transparent border-0 dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-label="User Menu">
                                <div class="">
                                    <img class="rounded-circle overflow-hidden" width="{{ $icon_size * 1.5 }}"
                                        height="{{ $icon_size * 1.5 }}" aspect-ratio="{{ $icon_ratio }}"
                                        src="http://placehold.it/35/35" alt="">
                                </div>

                            </button>
                            <ul class="dropdown-menu">
                                @auth
                                    @livewire('auth.logout')
                                @endauth
                            </ul>
                        </div>
                    </section>

                    <div class="dropdown mobile-topnav d-block d-md-none">
                        <button class="bg-transparent border-0 dropdown-toggle " type="button"
                            data-bs-toggle="dropdown" aria-label="Open Header Menu">
                            <x-icons.menu-fries></x-icons.menu-fries>
                        </button>
                        <ul class="dropdown-menu">
                            @auth
                                @livewire('auth.logout')
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </header>


        <main class=" mt-4 mt-md-5 {{ $main_content_class ?? '' }}">

            @yield('content')
            {{-- <x-html.test-elements></x-html.test-elements> --}}
        </main>



        <x-applicant.footer></x-applicant.footer>

    </body>

</x-html>
