<header class="top-nav sticky-md-top no-drag my-3" draggable="false" ondragstart="return;">
    <nav class="d-flex justify-content-between align-items-center">
        <div class="d-flex site-name home ms-md-5">
            <div class="align-content-center">
                <div class="">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </div>
            </div>

            <x-nav-link href="/" wire:navigate :active="request()->is('/')" class="no-hover ps-0  nav-link">
                <h1 class="fs-2  fw-bold text-primary">Powerlane</h1>
            </x-nav-link>

        </div>
        <div class="d-flex align-items-center fw-bold">

        </div>
    </nav>
</header>
