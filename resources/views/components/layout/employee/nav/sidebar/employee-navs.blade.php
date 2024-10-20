@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])
@use('App\Enums\UserRole')
@use('App\Enums\UserPermission')

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Main" txt_expanded="Menu">

    @if ($user->hasRole(UserRole::BASIC))
        @can(UserPermission::VIEW_EMPLOYEE_DASHBOARD)
            <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('employee/dashboard')" class="" nav_txt="Dashboard"
                :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endcan
    @endif

    @can(UserPermission::VIEW_ATTENDANCE)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Attendance"
            :default_icon="['src' => 'attendance', 'alt' => '']" :active_icon="['src' => 'attendance', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_PAYSLIPS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Payslips"
            :default_icon="['src' => 'clients', 'alt' => '']" :active_icon="['src' => 'clients', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_PERFORMANCE)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Performance"
            :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_LEAVES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Leaves"
            :default_icon="['src' => 'leaves', 'alt' => '']" :active_icon="['src' => 'leaves', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_OVERTIME)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Overtime"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_DOCUMENTS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Documents"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ISSUES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Issues"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @include('components.layout.employee.nav.sidebar.hr-navs', ['include' => 'main-menu'])

</x-layout.employee.nav.sidebar.nav-group>

@include('components.layout.employee.nav.sidebar.hr-navs', ['include' => 'ai-tools'])
