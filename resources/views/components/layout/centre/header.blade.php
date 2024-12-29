@props(['iconSize' => '25', 'iconRatio' => '1/1'])


<header class="top-nav sticky-md-top bg-primary">
    <nav class="d-flex justify-content-between align-items-center">
        <div class="d-flex site-name home ms-md-5">
            <div class="align-content-center">
                <div class="">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </div>
            </div>

            <x-nav-link href="/help-centre" :active="request()->is('/help-centre')"
                class="no-hover ps-0 fw-semibold d-flex align-items-center text-decoration-none">
                <h3 class="fw-bolder text-white mb-0">Help Centre</h1>
            </x-nav-link>
        </div>
        <div class="d-flex align-items-center fw-bold ">
            <section class="desktop-topnav d-none d-md-flex">
                <aside class="d-flex align-items-center">
                    <div class="dropdown ">
                        <button id="theme-toggle-btn" class="bg-transparent border-0 dropdown-toggle" type="button"
                            aria-label="Toggle light/dark theme" data-bs-toggle="dropdown">
                            <picture class="">
                                <source media="(min-width:2560px)" class=""
                                    srcset="{{ Vite::asset('resources/images/icons/moon-and-stars-69x69.webp') }}">
                                <source media="(min-width:768px)" class=""
                                    srcset="{{ Vite::asset('resources/images/icons/moon-and-stars-69x69.webp') }}">

                                <img class="icon" width="{{ $iconSize }}" height="{{ $iconSize }}"
                                    aspect-ratio="{{ $iconRatio }}"
                                    src="{{ Vite::asset('resources/images/icons/moon-and-stars-35x35.webp') }}" alt="">
                            </picture>
                        </button>
                        <ul class="dropdown-menu">
                            <li><span class="dropdown-item" data-isSystem="true">System</span></li>
                            <li><span class="dropdown-item" data-isSystem="false">Light</span></li>
                            <li><span class="dropdown-item" data-isSystem="false">Dark</span></li>
                        </ul>
                    </div>
                </aside>

            </section>

            <div class="dropdown mobile-topnav d-block d-md-none">
                <button class="bg-transparent border-0 dropdown-toggle " type="button" data-bs-toggle="dropdown"
                    aria-label="Open Header Menu">
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