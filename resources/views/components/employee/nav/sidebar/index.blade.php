@props(['sidebar_expanded' => true, 'icon_size' => '31px', 'icon_ratio' => '1/1', 'user' => ''])


<sidebar
    {{ $attributes->merge(['class' => $sidebar_expanded ? 'main-menu bg-primary active ' : 'main-menu bg-primary']) }}
    data-bs-scroll="true">

    <div class="d-flex site-name home ">

        <button class="d-flex  main-menu fw-semibold">
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

    <section class="overflow-y-auto">

        <div class="nav-list">
            {{ $slot }}
        </div>

    </section>

    <div class="user-bar bg-primary d-flex align-content-center py-4">
        <div class="px-4">
            <picture>
                <img class="rounded-circle overflow-hidden user-img" width="45px" height="45px"
                    aspect-ratio="{{ $icon_ratio }}" src="http://placehold.it/45/45" alt="">
            </picture>
        </div>
        <div class="flex-column me-auto user-info">
            <div>{{ trim($user['name'] ?? 'Unknown User') }}</div>
            <div>{{ trim($user['email'] ?? 'No email.') }}</div>
        </div>
        <div class="dropdown user-menu px-2">
            <button class="bg-transparent border-0 dropdown-toggle d-flex align-content-center" type="button"
                aria-label="User Menu" data-bs-toggle="dropdown">
                <i class="icon ellipsis text-white" data-lucide="more-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-item">Notif 1</li>
                <li class="dropdown-item">Notif 1</li>
                <li class="dropdown-item"> @livewire('auth.logout')</li>

            </ul>
        </div>

    </div>
</sidebar>
