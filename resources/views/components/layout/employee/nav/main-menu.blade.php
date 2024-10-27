@use('\App\Enums\UserRole', 'EnumsUserRole')
@props([
    'sidebar_expanded' => true,
    'icon_size' => '25',
    'icon_ratio' => '1/1',
    'user',
    'userPhoto',
    'defaultAvatar',
])


<div {{ $attributes->merge(['class' => 'container-fluid main-menu-container text-white']) }}>
    <x-layout.employee.nav.sidebar sidebar_expanded="{{ $sidebar_expanded }}" class="shadow">


        @includeWhen($guard->getName() == 'employee', 'components.layout.employee.nav.sidebar.employee-navs')

        @include('components.layout.employee.nav.sidebar.admin-navs')

    </x-layout.employee.nav.sidebar>
    <x-layout.employee.nav.topbar class="">

        <x-slot:topbar_right>
            <aside>
                <div class="dropdown">
                    <button id="theme-toggle-btn" class="bg-transparent border-0 dropdown-toggle" type="button"
                        aria-label="Toggle light/dark theme" data-bs-toggle="dropdown">
                        <picture class="">
                            <source media="(min-width:2560px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/moon-and-stars-69x69.webp') }}">
                            <source media="(min-width:768px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/moon-and-stars-69x69.webp') }}">

                            <img class="icon" width="{{ $icon_size }}" aspect-ratio="{{ $icon_ratio }}"
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

            <div class="rounded-circle overflow-hidden">
                <picture>
                    {{-- <source media="(min-width:2560px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}">
                    <source media="(min-width:768px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}"> --}}

                    <img class="" width="{{ $icon_size * 1.25 }}" height="{{ $icon_size * 1.25 }}"
                        aspect-ratio="{{ $icon_ratio }}" src="{{ $userPhoto ?? $defaultAvatar }}"
                        onerror="this.onerror=null;this.src='http://placehold.it/45/45';" alt="">
                </picture>
            </div>


        </x-slot:topbar_right>

    </x-layout.employee.nav.topbar>
</div>
