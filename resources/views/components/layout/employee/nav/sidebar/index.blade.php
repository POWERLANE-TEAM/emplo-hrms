@props(['sidebar_expanded' => true])
@aware(['user', 'userPhoto', 'iconSize' => '31px', 'iconRatio' => '1/1'])


<sidebar
    {{ $attributes->merge(['class' => $sidebar_expanded ? 'main-menu bg-primary active ' : 'main-menu bg-primary']) }}
    data-bs-scroll="true">

    <div class="d-flex site-name home ">

        <button type="button" class="d-flex  main-menu fw-semibold">
            <div class="align-content-center">
                <div class="">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </div>
            </div>
            <h1 class="fs-2  fw-bolder company-name mb-0 me-3 me-md-0 me-xl-4 text-white">Powerlane</h1>

            <div class="bg-white rounded-circle d-inline-block align-content-center text-primary">
                <i data-lucide="chevron-right"></i>
            </div>
        </button>
    </div>

    <section class="overflow-y-auto thin-hidden-scrollbar">

        <div class="nav-list">
            {{ $slot }}
        </div>

    </section>
</sidebar>
