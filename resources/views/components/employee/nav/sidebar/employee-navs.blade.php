@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])

<x-employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Main" txt_expanded="Menu">

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('dashboard')" class="" nav_txt="Dashboard" :default_icon="['src' => 'dashboard', 'alt' => '']"
        :active_icon="['src' => 'dashboard', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Attendance" :default_icon="['src' => 'attendance', 'alt' => '']"
        :active_icon="['src' => 'attendance', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Payslips" :default_icon="['src' => 'clients', 'alt' => '']"
        :active_icon="['src' => 'clients', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Performance"
        :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Leaves"
        :default_icon="['src' => 'leaves', 'alt' => '']" :active_icon="['src' => 'leaves', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    {{-- SHOW IF HR AND EMPLOYEE --}}
    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Overtime"
        :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Documents"
        :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

    <x-employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Issues"
        :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
    </x-employee.nav.sidebar.nav-item>

</x-employee.nav.sidebar.nav-group>
