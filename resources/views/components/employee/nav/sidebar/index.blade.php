@props(['sidebar_expanded' => true, 'icon_size' => '31px', 'icon_ratio' => '1/1'])


<sidebar
    {{ $attributes->merge(['class' => $sidebar_expanded ? 'main-menu bg-primary active ' : 'main-menu bg-primary']) }}
    data-bs-scroll="true">

    <div class="d-flex site-name home ">

        <button class="d-flex  main-menu ">
            <div class="align-content-center">
                <div class="">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </div>
            </div>
            <h1 class="fs-2 company-name mb-0 me-3 me-md-0 me-xl-4 text-white">Powerlane</h1>

            <div class="bg-white rounded-circle d-inline-block align-content-center"><i data-lucide="chevron-right"></i>
            </div>
        </button>
    </div>

    <section class="overflow-y-auto">

        <div class="nav-list">
            {{ $slot }}
        </div>

    </section>

    <div class="position-absolute bottom-0 bg-primary d-flex ">
        <div>
            <picture>
                {{-- <source media="(min-width:2560px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}">
                <source media="(min-width:768px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}"> --}}

                <img class="rounded-circle overflow-hidden" width="45px" aspect-ratio="{{ $icon_ratio }}"
                    src="http://placehold.it/45/45" alt="">
            </picture>
        </div>
        <div class="d-flex flex-column">
            <div>Maria Kilnsey</div>
            <div>kny.maria@gmail.com</div>
        </div>
        <div>
            <i data-lucide="more-vertical"></i>
        </div>
    </div>
</sidebar>
