<x-html title="Home">

    <x-html.head description=" This is dashboard">
        <title>Home Page</title>
        @php
            $nonce = csp_nonce();
        @endphp
        @livewireStyles(['nonce' => $nonce])
        @livewireScripts(['nonce' => $nonce])
        {{-- @livewireScriptConfig(['nonce' => $nonce]) --}}

        <script src="build/assets/nbp.min.js" defer></script>
        @vite(['resources/js/index.js'])

        <script src="https://unpkg.com/lucide@latest"></script>


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
                    left: 17%;
                    transform: scaleX(1.30) scaleY(1.1);
                }

                svg.right-circle {
                    position: absolute;
                    right: calc(-96px + 1vw);
                    top: 87vh;
                    z-index: -10;
                }

                svg.left-circle {
                    position: absolute;
                    // left: -3%;
                    left: calc(-32px + 1vw);
                    top: 47vh;
                    z-index: -10;
                }
            }
        </style>

        {{-- END: Critical Styles --}}

    </x-html.head>

    <body class="">

        <section class="top-vector">

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

                <img class="green-wave" src="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.png') }}"
                    width="600px" height="300px" alt="">
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

        <header class="top-nav sticky-md-top">
            <nav class="d-flex justify-content-between align-items-center">
                <div class="d-flex site-name home ms-md-5">
                    <div class="align-content-center">
                        <div class="">
                            <x-html.pri-sm-logo></x-html.pri-sm-logo>
                        </div>
                    </div>

                    <x-nav-link href="/" :active="request()->is('/')" class="no-hover ps-0  nav-link">
                        <h1 class="fs-2 text-white">Powerlane</h1>
                    </x-nav-link>
                </div>
                <div class="d-flex align-items-center fw-bold">
                    <section class="desktop-topnav d-none d-md-flex">
                        <x-nav-link href="/about-us" class="nav-link" :active="request()->is('about-us')">About</x-nav-link>
                        <x-nav-link href="/contact-us" class="nav-link" :active="request()->is('contact-us')">Contact</x-nav-link>
                        <x-nav-link type="button" class="btn btn-secondary bg-white text-primary nav-link">Sign
                            Up
                        </x-nav-link>
                    </section>

                    <div class="dropdown mobile-topnav d-block d-md-none">
                        <button class="bg-transparent border-0 dropdown-toggle " type="button"
                            data-bs-toggle="dropdown" aria-label="Open Notifications">
                            <x-icons.menu-fries></x-icons.menu-fries>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <x-nav-link href="/about-us" class="nav-link" :active="request()->is('about-us')">About</x-nav-link>
                            </li>
                            <li class="dropdown-item">
                                <x-nav-link href="/contact-us" class="nav-link" :active="request()->is('contact-us')">Contact</x-nav-link>
                            </li>
                            <li class="dropdown-item">
                                <x-nav-link type="button" class="btn btn-secondary bg-white text-primary nav-link">Sign
                                    Up
                                </x-nav-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>


        <main class="main container-fluid">

            <section class="first-section ">
                <div class="row">
                    <div class="left col-12 col-md-6 align-content-center">
                        <div class="text-primary fs-1 fw-bold tw-tracking-wide" aria-label="We are hiring!">
                            We are hiring!
                        </div>
                        <p class="fs-4 fw-medium">
                            Exciting career opportunities available.
                            <br>
                            Explore our open job positions today.
                        </p>

                    </div>

                    <div class="right col-12 col-md-6 ">
                        <div class="illus ">
                            <div class="box-icon">
                                <i data-lucide="search"></i>
                            </div>
                            <picture>
                                <source media="(min-width:2560px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xl.webp') }}">
                                <source media="(min-width:1200px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg.webp') }}">
                                <source media="(min-width:768px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">
                                <source media="(min-width:576px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-sm.webp') }}">

                                <img src="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}" class="sapien"
                                    width="300px" height="250px" alt="">
                            </picture>
                        </div>
                        <div class="box-icon">
                            <i data-lucide="briefcase"></i>
                        </div>
                    </div>
                </div>
            </section>

            <section class="second-section">
                @livewire('guest.job-search-input')
                <div class="tw-px-[5rem] tw-pt-[2.5rem] tw-pb-[1rem]">
                    <em>
                        Currently <span></span> <span>jobs</span> available
                    </em>
                </div>
                <section class="job-listing d-flex row gap-5 mb-5">

                    @livewire('guest.jobs-list-card', ['lazy' => true])

                    @livewire('guest.job-view-pane', ['lazy' => true])

                </section>

            </section>

            <div class="modal fade signUp-form" id="signUpForm" aria-hidden="true"
                aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
                    <div class="modal-content p-md-4 ">
                        <div class="modal-header border-0">

                            <button type="button" class="btn-close p-1 p-md-3 mt-md-n4 me-md-n3"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body px-md-5 py-md-4">
                            @livewire('guest.forms.sign-up', ['lazy' => true])
                        </div>
                    </div>


                </div>

            </div>


        </main>

        {{-- <x-html.test-elements></x-html.test-elements> --}}

        <x-guest.footer></x-guest.footer>

    </body>

</x-html>
