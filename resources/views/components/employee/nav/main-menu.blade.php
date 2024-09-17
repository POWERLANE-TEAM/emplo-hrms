@props(['sidebar_expanded' => true, 'icon_size' => '25', 'icon_ratio' => '1/1'])

<div {{ $attributes->merge(['class' => 'container-fluid main-menu-container text-white']) }}>
    <x-employee.nav.sidebar sidebar_expanded="{{ $sidebar_expanded }}" class="shadow" icon_size="{{ $icon_size }}"
        icon_ratio="{{ $icon_ratio }}">
        @php

            $user = Auth::user()->load('role');

            // dd($user);

        @endphp

        {{-- @includeWhen($user->role->user_role_name == 'USER', 'components.employee.nav.sidebar.employee-navs') --}}

        @includeWhen($user->role->user_role_name == 'HR MANAGER', 'components.employee.nav.sidebar.hr-navs')

        @includeWhen(
            $user->role->user_role_name == 'SYSADMIN',
            'components.employee.nav.sidebar.head-admin-navs')

    </x-employee.nav.sidebar>
    <x-employee.nav.topbar class="">

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
                        aspect-ratio="{{ $icon_ratio }}" src="http://placehold.it/20/20" alt="">
                </picture>
            </div>


        </x-slot:topbar_right>

    </x-employee.nav.topbar>
</div>
