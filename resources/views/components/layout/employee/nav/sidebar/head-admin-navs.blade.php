@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Management">
    {{--
    ITEMS START
 --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('dashboard')" class="" nav_txt="Dashboard"
        :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    {{-- SHOW IF HEAD ADMIN ONLY --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Accounts"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('employee')" class="" nav_txt="Employees"
        :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    {{-- SHOW IF HEAD ADMIN ONLY --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Calendar Manager"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    {{-- SHOW IF HEAD ADMIN ONLY --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Calendar Manager"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    {{-- SHOW IF HEAD ADMIN ONLY --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Policies"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    {{-- SHOW IF HEAD ADMIN ONLY --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Announcements"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

</x-layout.employee.nav.sidebar.nav-group>

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Configuration"
    txt_expanded="">

    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Performance"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Forms"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>

</x-layout.employee.nav.sidebar.nav-group>
