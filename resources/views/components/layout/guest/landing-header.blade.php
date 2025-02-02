<header class="top-nav">
    <nav class="d-flex justify-content-between align-items-center">
        <div class="d-flex site-name home ms-md-5">
            <div class="align-content-center">
                <div class="">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </div>
            </div>

            <x-nav-link href="/" :active="request()->is('/')" class="no-hover ps-0">
                <h1 class="fs-2 mb-0 fw-bold text-white">Powerlane</h1>
            </x-nav-link>
        </div>
        <div class="d-flex align-items-center fw-bold">
            <section class="desktop-topnav d-none d-md-flex">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>

                <a href="/hiring" class="nav-link {{ request()->is('hiring') ? 'active' : '' }}" id="job-listtings">Job
                    Listings</a>

                <!-- <x-nav-link href="/contact-us" class="nav-link" :active="request()->is('contact-us')">Contact</x-nav-link> -->

                @guest
                    <a href="/login" class="nav-link btn btn-lg btn-outline-secondary border border-white 
                        {{ request()->is('login') ? 'active' : '' }}">
                        Sign In
                    </a>

                @endguest

                @auth
                    @livewire('auth.logout', ['class' => 'btn btn-lg text-primary bg-white'])
                @endauth

            </section>

            <div class="dropdown mobile-topnav d-block d-md-none">
                <button class="bg-transparent border-0 dropdown-toggle " type="button" data-bs-toggle="dropdown"
                    aria-label="Open Notifications">
                    <x-icons.menu-fries></x-icons.menu-fries>
                </button>
                <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <x-nav-link href="/" class="nav-link" :active="request()->is('/')">Home</x-nav-link>
                    </li>
                    <li class="dropdown-item">
                        <x-nav-link href="/hiring" class="nav-link" :active="request()->is('hiring')">Job
                            Listings</x-nav-link>
                    </li>
                    <li class="dropdown-item">
                        <x-nav-link href="/contact-us" class="nav-link"
                            :active="request()->is('contact-us')">Contact</x-nav-link>
                    </li>
                    @guest
                        <li class="dropdown-item">
                            <x-nav-link href="/login" class="btn btn-secondary bg-white text-primary nav-link">Sign
                                In
                            </x-nav-link>
                        </li>

                        <li class="dropdown-item">
                            <x-nav-link href="" class="btn btn-secondary bg-white text-primary nav-link">Sign
                                In
                            </x-nav-link>
                        </li>
                    @endguest

                    @auth
                        @livewire('auth.logout')
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @guest
        @livewire('auth.google-one-tap')
    @endguest

</header>