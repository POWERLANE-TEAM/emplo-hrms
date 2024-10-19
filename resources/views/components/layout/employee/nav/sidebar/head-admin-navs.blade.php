@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])
@use('App\Enums\UserPermission')
@use('App\Enums\GuardType')

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Management">

    @can(UserPermission::VIEW_ADMIN_DASHBOARD)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('admin/dashboard')" class="" nav_txt="Dashboard"
            :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::CREATE_EMPLOYEE_ACCOUNT)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Accounts"
            :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT)
        {{-- insert submenu --}}
    @endcan

    {{-- @can(UserPermission::VIEW_EMPLOYEE_MANAGEMENT) --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('employee')" class="" nav_txt="Employees"
        :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    {{-- @endcan --}}

    @can(UserPermission::VIEW_CALENDAR_MANAGEMENT)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Calendar Manager"
            :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- @can(UserPermission::VIEW_JOB_MANAGEMENT) --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Job Board"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    {{-- @endcan --}}

    {{-- @can(UserPermission::VIEW_POLICY_MANAGEMENT) --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Policies"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    {{-- @endcan --}}

    {{-- @can(UserPermission::VIEW_ANNOUNCEMENT_MANAGEMENT) --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Announcements"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    {{-- @endcan --}}

</x-layout.employee.nav.sidebar.nav-group>

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Configuration"
    txt_expanded="">

    {{-- @can(UserPermission::VIEW_PERFORMANCE_CONFIGURATION) --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Performance"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    {{-- @endcan --}}

    {{-- @can(UserPermission::VIEW_FORMS_CONFIGURATION) --}}
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Forms"
        :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    {{-- @endcan --}}

</x-layout.employee.nav.sidebar.nav-group>
