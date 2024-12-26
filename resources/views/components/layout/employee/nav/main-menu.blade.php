@use('\App\Enums\UserRole', 'EnumsUserRole')
@props([
    'sidebar_expanded' => false,
    'iconSize' => '25',
    'iconRatio' => '1/1',
    'user',
    'userPhoto',
])


<div {{ $attributes->merge(['class' => 'container-fluid main-menu-container text-white']) }}>
    <x-layout.employee.nav.sidebar sidebar_expanded="{{ $sidebar_expanded }}" class="shadow">

        @include('components.layout.employee.nav.sidebar.employee-navs')

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

                            <img class="icon" width="{{ $iconSize }}" aspect-ratio="{{ $iconRatio }}"
                                src="{{ Vite::asset('resources/images/icons/moon-and-stars-35x35.webp') }}" alt="">
                        </picture>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><span class="dropdown-item" data-isSystem="true" role="button">System</span></li>
                        <li><span class="dropdown-item" data-isSystem="false" role="button">Light</span></li>
                        <li><span class="dropdown-item" data-isSystem="false" role="button">Dark</span></li>
                    </ul>
                </div>
            </aside>

            <x-notif-dropdown>
                @if (!request()->routeIs($routePrefix . '.notifications'))
                    <div class="card border-0 py-3 notification-container visible-gray-scrollbar show">

                        <!-- Header -->
                        <div>
                            <div class="row px-4">
                                <div class="col-md-10">
                                    <h4 class="mb-0 fw-bold">Notifications</h4>
                                </div>
                                <div class="col-md-2 text-end mb-3">
                                    <a wire:navigate href="{{ route($routePrefix . '.notifications') }}"
                                        class="text-muted green-hover">
                                        <span data-bs-toggle="tooltip" title="See all notifications">
                                            <i data-lucide="list" class="icon icon-large"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <livewire:notifications.notifs />
                    </div>
                @endif
            </x-notif-dropdown>

            <div class="overflow-hidden">
                <div class="user-menu px-2">
                    <button id="user-prof-btn" class="bg-transparent border-0" type="button" aria-label="User Menu"
                        onclick="toggleUserDropdown()" data-bs-toggle="dropdown">
                        <img class="rounded-circle" width="{{ $iconSize * 1.25 }}" height="{{ $iconSize * 1.25 }}"
                            aspect-ratio="{{ $iconRatio }}" src="{{ $userPhoto }}"
                            onerror="this.onerror=null;this.src='http://placehold.it/45/45';" alt="">
                    </button>
                    <ul id="dropdown-menu" class="dropdown-menu dropdown-menu-end px-2" role="menu">
                        <a href="{{ route($routePrefix . '.profile') }}" class="text-decoration-none">
                            <li class="dropdown-item" role="button">
                                Profile
                            </li>
                        </a>
                        <a href="{{ route($routePrefix . '.settings') }}" class="text-decoration-none">
                            <li class="dropdown-item" role="button" class="mt-5">Settings & Privacy</li>
                        </a>
                        <li class="dropdown-item" role="button">@livewire('auth.logout')</li>
                    </ul>
                </div>
            </div>

        </x-slot:topbar_right>

    </x-layout.employee.nav.topbar>
</div>