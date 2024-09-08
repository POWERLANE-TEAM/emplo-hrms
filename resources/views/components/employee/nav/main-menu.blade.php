@props(['sidebar_expanded' => true, 'icon_size' => '25', 'icon_ratio' => '1/1'])

<div {{ $attributes->merge(['class' => 'container-fluid main-menu-container text-white']) }}>
    <x-employee.nav.sidebar sidebar_expanded="{{ $sidebar_expanded }}" class="shadow" icon_size="{{ $icon_size }}"
        icon_ratio="{{ $icon_ratio }}">

        <x-employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Main" txt_expanded="Menu"
            icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}">
            <x-employee.nav.sidebar.nav-item href="/employee" :active="request()->is('employee')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Dashboard" :default_icon="['src' => 'dashboard', 'alt' => '']"
                :active_icon="['src' => 'dashboard', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="/employees" :active="request()->is('employees')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Applicants" :default_icon="['src' => 'applicants', 'alt' => '']"
                :active_icon="['src' => 'applicants', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="/employees" :active="request()->is('employees')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Employees" :default_icon="['src' => 'employee', 'alt' => '']"
                :active_icon="['src' => 'employee', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Attendance"
                :default_icon="['src' => 'attendance', 'alt' => '']" :active_icon="['src' => 'attendance', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Leaves" :default_icon="['src' => 'leaves', 'alt' => '']"
                :active_icon="['src' => 'leaves', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Daily" :default_icon="['src' => 'daily', 'alt' => '']"
                :active_icon="['src' => 'daily', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Performance"
                :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Clients" :default_icon="['src' => 'clients', 'alt' => '']"
                :active_icon="['src' => 'clients', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>
        </x-employee.nav.sidebar.nav-group>

        <x-employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="AI Tools" txt_expanded=""
            icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}">
            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Matrix Projector"
                :default_icon="['src' => 'matrix-projector', 'alt' => '']" :active_icon="['src' => 'matrix-projector', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

            <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}" nav_txt="Skill Evaluator"
                :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
            </x-employee.nav.sidebar.nav-item>

        </x-employee.nav.sidebar.nav-group>

    </x-employee.nav.sidebar>
    <x-employee.nav.topbar class="" icon_size="{{ $icon_size }}" icon_ratio="{{ $icon_ratio }}">

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
