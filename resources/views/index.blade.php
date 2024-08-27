<x-html title="Home">

    <x-html.head description=" This is dashboard">
        <title>Home Page</title>
        @php
            $nonce = csp_nonce();
        @endphp
        @livewireStyles(['nonce' => $nonce])
        @livewireScripts(['nonce' => $nonce])
        {{-- @livewireScriptConfig(['nonce' => $nonce]) --}}

        @vite(['resources/js/index.js'])

        <script src="https://unpkg.com/lucide@latest"></script>

    </x-html.head>

    <body class="max-w-[100%] tw-overflow-x-hidden">

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
                    alt="">
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
                        @livewire('applicant.buttons.sign-up')
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
                                @livewire('applicant.buttons.sign-up')
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
                        <h3 class="text-primary fs-1 fw-bold tw-tracking-wide">
                            We are hiring!
                        </h3>
                        <p class="fs-4 fw-medium">
                            Exciting career opportunities available.
                            <br>
                            Explore our open job positions today.
                        </p>

                    </div>

                    <div class="right col-12 col-md-6 ">
                        <div class="tw-relative tw-translate-x-6 tw-translate-y-10">
                            <div class="box-icon">
                                <i data-lucide="search"></i>
                            </div>
                            <picture>
                                <source media="(min-width:1400px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-2044x1816.png') }}">
                                <source media="(min-width:1200px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-1022x908.png') }}">
                                <source media="(min-width:768px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-511x454.png') }}">
                                <source media="(min-width:576px)" class=""
                                    srcset="{{ Vite::asset('resources/images/illus/sapiens-1-384x341.png') }}">
                                <img src="{{ Vite::asset('resources/images/illus/sapiens-1-1022x908.png') }}"
                                    class="sapien" alt="">
                            </picture>
                        </div>
                        <div class="box-icon">
                            <i data-lucide="briefcase"></i>
                        </div>
                    </div>
                </div>
            </section>

            <section class="second-section">
                <x-input.search-group container_class="col-8 col-md-4" class="justify-content-center w-100">
                    <x-slot:right_icon>
                        <i data-lucide="search"></i>
                    </x-slot:right_icon>
                    <x-input.search type="search" placeholder="Search job titles or companies"></x-input.search>
                </x-input.search-group>
                <div class="tw-px-[5rem] tw-pt-[2.5rem] tw-pb-[1rem]">
                    <em>
                        Currently <span></span> <span>jobs</span> available
                    </em>
                </div>
                <section class="job-listing d-flex row tw-gap-12 ">




                    @livewire('applicant.jobs-list-card')


                    <article class="job-view tab-content col-12 col-md-6">
                        <div class="job-content tab-pane fade show active card border-0 bg-secondary w-100 "
                            id="#1-tab-pane" role="tabpanel" aria-labelledby="-tab">
                            <div class="d-flex">
                                <header>
                                    <hgroup>
                                        <h4 class="card-title text-primary fw-bold mb-0">Job Position</h4>
                                        <p class="fs-6 text-black ">Job Location</p>
                                    </hgroup>
                                    <a href="" hreflang="en-PH" class="btn btn-primary mt-1 mb-4"
                                        role="navigation" aria-label="Apply">Apply <span><i
                                                data-lucide="external-link"></i></span></a>

                                    <p class="job-descr card-text">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                                        possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                                        quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                                        natus?
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                                        possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                                        quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                                        natus?
                                    </p>
                                    <button href=""
                                        class="bg-transparent border border-0 text-decoration-underline text-black ps-0">
                                        Show More <span><i data-lucide="chevron-down"></i></span>
                                    </button>
                                </header>
                                <div>
                                    <button class="bg-transparent border border-0">
                                        <i data-lucide="more-vertical"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>

            </section>

        </main>

        {{-- <x-html.test-elements></x-html.test-elements> --}}

        <x-guest.footer></x-guest.footer>

    </body>

</x-html>
