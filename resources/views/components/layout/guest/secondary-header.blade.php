<header class="top-nav sticky-md-top no-drag my-3" draggable="false" ondragstart="return;">
    <nav class="d-flex justify-content-between align-items-center">
        <div class="d-flex site-name home ms-md-5">
            <div class="align-content-center">
                <div class="">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </div>
            </div>

            <a href="/hiring" class="no-hover text-decoration-none p-0 {{ request()->is('/') ? 'active' : '' }}">
                <p class="fs-3 mb-0 fw-bold text-primary">Powerlane</p>
            </a>


            @livewire('auth.google-one-tap')
        </div>
        <div class="d-flex align-items-center fw-bold">

        </div>
    </nav>
</header>