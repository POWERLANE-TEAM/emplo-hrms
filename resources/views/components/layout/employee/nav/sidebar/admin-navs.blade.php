@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])
@use('App\Enums\UserPermission')
@use('App\Enums\GuardType')

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Management">

    @can(UserPermission::VIEW_ADMIN_DASHBOARD)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" class="" nav_txt="Dashboard"
            :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ACCOUNT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.accounts') }}" :active="request()->routeIs('admin.accounts')" class="" nav_txt="Accounts"
            :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_EMPLOYEE_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')" class="" nav_txt="Employees"
            :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_CALENDAR_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.calendar') }}" :active="request()->routeIs('admin.calendar')" class="" nav_txt="Calendar"
            :default_icon="['src' => 'calendar', 'alt' => '']" :active_icon="['src' => 'calendar', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_JOB_LISTING_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.job-listing') }}" :active="request()->routeIs('admin.job-listing')" class="" nav_txt="Job Listing"
            :default_icon="['src' => 'job-listing', 'alt' => '']" :active_icon="['src' => 'job-listing', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_POLICY_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.policy') }}" :active="request()->routeIs('admin.policy')" class="" nav_txt="Policies"
            :default_icon="['src' => 'policies', 'alt' => '']" :active_icon="['src' => 'policy', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ANNOUNCEMENT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.announcement') }}" :active="request()->routeIs('admin.announcement')" class="" nav_txt="Announcements"
            :default_icon="['src' => 'announcement', 'alt' => '']" :active_icon="['src' => 'announcement', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Configuration"
    txt_expanded="">

    @can(UserPermission::VIEW_PERFORMANCE_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.performance') }}" :active="request()->routeIs('admin.performance')" class="" nav_txt="Performance"
            :default_icon="['src' => 'performance', 'alt' => '']" :active_icon="['src' => 'performance', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_FORM_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route('admin.form') }}" :active="request()->routeIs('admin.form')" class="" nav_txt="Forms"
            :default_icon="['src' => 'forms', 'alt' => '']" :active_icon="['src' => 'forms', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>
