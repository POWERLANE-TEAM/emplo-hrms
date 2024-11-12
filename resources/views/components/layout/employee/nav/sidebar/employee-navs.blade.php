@aware(['icon_size' => '31px', 'icon_ratio' => '1/1', 'user'])
@use('App\Enums\UserRole')
@use('App\Enums\UserPermission')

{{--
* |--------------------------------------------------------------------------
* | Main Menu
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Main" txt_expanded="Menu">

    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([UserPermission::VIEW_EMPLOYEE_DASHBOARD, UserPermission::VIEW_HR_MANAGER_DASHBOARD,
        UserPermission::VIEW_ADMIN_DASHBOARD])
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.dashboard') }}" :active="request()->routeIs($routePrefix . '.dashboard')"
            class="tw-order-[0]" nav_txt="Dashboard" :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan


    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $nav_attendance_order = $user->hasPermissionTo(UserPermission::VIEW_ALL_ATTENDANCE) ? 4 : 2;
    @endphp
    @canAny([UserPermission::VIEW_ATTENDANCE, UserPermission::VIEW_ALL_ATTENDANCE])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.attendances')"
            class="tw-order-[{{ $nav_attendance_order }}]" nav_txt="Attendance" :default_icon="['src' => 'attendance', 'alt' => '']" :active_icon="['src' => 'attendance', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $nav_payslip_order = $user->hasPermissionTo(UserPermission::VIEW_ALL_PAYSLIPS) ? 7 : 3;
    @endphp
    @canAny([UserPermission::VIEW_PAYSLIPS, UserPermission::VIEW_ALL_PAYSLIPS])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.payslips')" class="tw-order-[{{ $nav_payslip_order }}]"
            nav_txt="Payslips" :default_icon="['src' => 'payslips', 'alt' => '']" :active_icon="['src' => 'payslips', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $nav_performance_order = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES) ? 8 : 4;
    @endphp
    @canAny([UserPermission::VIEW_PERFORMANCE, UserPermission::VIEW_ALL_PERFORMANCE])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.performances')"
            class="tw-order-[{{ $nav_performance_order }}]" nav_txt="Performance" :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([UserPermission::VIEW_LEAVES, UserPermission::VIEW_ALL_LEAVES])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.leaves')" class="tw-order-[5]" nav_txt="Leaves"
            :default_icon="['src' => 'leaves', 'alt' => '']" :active_icon="['src' => 'leaves', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([UserPermission::VIEW_OVERTIME, UserPermission::VIEW_ALL_OVERTIME])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.overtimes')" class="tw-order-[6]" nav_txt="Overtime"
            :default_icon="['src' => 'overtime', 'alt' => '']" :active_icon="['src' => 'overtime', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, Supervisor --}}
    @canAny([UserPermission::VIEW_DOCUMENTS])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.documents')" class="tw-order-[7]" nav_txt="Documents"
            :default_icon="['src' => 'documents', 'alt' => '']" :active_icon="['src' => 'documents', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, Supervisor --}}
    @can(UserPermission::VIEW_ISSUES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.issues')" class="tw-order-[8]" nav_txt="Issues"
            :default_icon="['src' => 'issues', 'alt' => '']" :active_icon="['src' => 'issues', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_APPLICANTS)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.applicants') }}" :active="request()->routeIs($routePrefix . '.applicants')"
            class="tw-order-[2]" nav_txt="Applicants" :default_icon="['src' => 'applicants', 'alt' => '']" :active_icon="['src' => 'applicants', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_EMPLOYEES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.employees')" class="tw-order-[3]" nav_txt="Employees"
            :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_RELATIONS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.relations')" class="tw-order-[11]" nav_txt="Relations"
            :default_icon="['src' => 'relations', 'alt' => '']" :active_icon="['src' => 'relations', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_ACCOUNT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.create-account') }}" :active="request()->routeIs($routePrefix . '.create-account')"
            class="" nav_txt="Accounts" :default_icon="['src' => 'accounts', 'alt' => '']" :active_icon="['src' => 'accounts', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_EMPLOYEE_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.create-job-family') }}" :active="request()->routeIs($routePrefix . '.create-job-family')"
            class="" nav_txt="Organization" :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_CALENDAR_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.calendar') }}" :active="request()->routeIs($routePrefix . '.calendar-manager')"
            class="" nav_txt="Calendar" :default_icon="['src' => 'calendar-manager', 'alt' => '']" :active_icon="['src' => 'calendar', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_JOB_LISTING_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.add-open-position') }}" :active="request()->routeIs($routePrefix . '.add-open-position')"
            class="" nav_txt="Job Board" :default_icon="['src' => 'jobboard', 'alt' => '']" :active_icon="['src' => 'job-listing', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_POLICY_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.policy') }}" :active="request()->routeIs($routePrefix . '.policy')"
            class="" nav_txt="Policies" :default_icon="['src' => 'documents', 'alt' => '']" :active_icon="['src' => 'documents', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_ANNOUNCEMENT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.create-announcement') }}"
            :active="request()->routeIs($routePrefix . '.create-announcement')" class="" nav_txt="Announcements" :default_icon="['src' => 'announcements', 'alt' => '']" :active_icon="['src' => 'announcements', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
* |--------------------------------------------------------------------------
* | Configuration
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Config"
    txt_expanded="Configuration">

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_PERFORMANCE_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.categories') }}" :active="request()->routeIs($routePrefix . '.categories')"
            class="" nav_txt="Performance" :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_FORM_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.pre-emp-reqs') }}" :active="request()->routeIs($routePrefix . '.pre-emp-reqs')"
            class="" nav_txt="Forms" :default_icon="['src' => 'forms', 'alt' => '']" :active_icon="['src' => 'forms', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
* |--------------------------------------------------------------------------
* | AI Tools
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="AI Tools" txt_expanded="">

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_TALENT_EVALUATOR)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Resume Evaluator"
            :default_icon="['src' => 'resume-evaluator', 'alt' => '']" :active_icon="['src' => 'resume-evaluator', 'alt' => '']">
            :default_icon="['src' => 'resume-evaluator', 'alt' => '']" :active_icon="['src' => 'resume-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_PLAN_GENERATOR)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Plan Generator"
            :default_icon="['src' => 'plan-generator', 'alt' => '']" :active_icon="['src' => 'plan-generator', 'alt' => '']">
            :default_icon="['src' => 'plan-generator', 'alt' => '']" :active_icon="['src' => 'plan-generator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>
