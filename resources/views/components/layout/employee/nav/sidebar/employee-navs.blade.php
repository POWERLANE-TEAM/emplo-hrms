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
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.dashboard') }}" :active="request()->is($guard . '.dashboard')" class="tw-order-[0]"
            nav_txt="Dashboard" :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $nav_attendance_order = $user->hasPermissionTo(UserPermission::VIEW_ALL_ATTENDANCE) ? 4 : 2;
    @endphp
    @canAny([UserPermission::VIEW_ATTENDANCE, UserPermission::VIEW_ALL_ATTENDANCE])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.attendances')"
            class="tw-order-[{{ $nav_attendance_order }}]" nav_txt="Attendance" :default_icon="['src' => 'attendance', 'alt' => '']" :active_icon="['src' => 'attendance', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $nav_payslip_order = $user->hasPermissionTo(UserPermission::VIEW_ALL_PAYSLIPS) ? 7 : 3;
    @endphp
    @canAny([UserPermission::VIEW_PAYSLIPS, UserPermission::VIEW_ALL_PAYSLIPS])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.payslips')" class="tw-order-[{{ $nav_payslip_order }}]"
            nav_txt="Payslips" :default_icon="['src' => 'clients', 'alt' => '']" :active_icon="['src' => 'clients', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $nav_performance_order = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES) ? 8 : 4;
    @endphp
    @canAny([UserPermission::VIEW_PERFORMANCE, UserPermission::VIEW_ALL_PERFORMANCE])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.performances')"
            class="tw-order-[{{ $nav_performance_order }}]" nav_txt="Performance" :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([UserPermission::VIEW_LEAVES, UserPermission::VIEW_ALL_LEAVES])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.leaves')" class="tw-order-[5]" nav_txt="Leaves"
            :default_icon="['src' => 'leaves', 'alt' => '']" :active_icon="['src' => 'leaves', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([UserPermission::VIEW_OVERTIME, UserPermission::VIEW_ALL_OVERTIME])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.overtimes')" class="tw-order-[6]" nav_txt="Overtime"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, Supervisor --}}
    @canAny([UserPermission::VIEW_DOCUMENTS])
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.documents')" class="tw-order-[7]" nav_txt="Documents"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, Supervisor --}}
    @can(UserPermission::VIEW_ISSUES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.issues')" class="tw-order-[8]" nav_txt="Issues"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_APPLICANTS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.applicants')" class="tw-order-[2]" nav_txt="Applicants"
            :default_icon="['src' => 'applicants', 'alt' => '']" :active_icon="['src' => 'applicants', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_EMPLOYEES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.employees')" class="tw-order-[3]" nav_txt="Employees"
            :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_RELATIONS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is($guard . '.relations')" class="tw-order-[11]" nav_txt="Relations"
            :default_icon="['src' => 'clients', 'alt' => '']" :active_icon="['src' => 'clients', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ACCOUNT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.accounts') }}" :active="request()->routeIs($guard . '.accounts')" class=""
            nav_txt="Accounts" :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_EMPLOYEE_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.employees') }}" :active="request()->routeIs($guard . '.employees')" class=""
            nav_txt="Employees" :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_CALENDAR_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.calendar') }}" :active="request()->routeIs($guard . '.calendar')" class=""
            nav_txt="Calendar" :default_icon="['src' => 'calendar', 'alt' => '']" :active_icon="['src' => 'calendar', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_JOB_LISTING_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.job-listing') }}" :active="request()->routeIs($guard . '.job-listing')" class=""
            nav_txt="Job Listing" :default_icon="['src' => 'job-listing', 'alt' => '']" :active_icon="['src' => 'job-listing', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_POLICY_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.policy') }}" :active="request()->routeIs($guard . '.policy')" class=""
            nav_txt="Policies" :default_icon="['src' => 'policies', 'alt' => '']" :active_icon="['src' => 'policy', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ANNOUNCEMENT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.announcement') }}" :active="request()->routeIs($guard . '.announcement')" class=""
            nav_txt="Announcements" :default_icon="['src' => 'announcement', 'alt' => '']" :active_icon="['src' => 'announcement', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
 * |--------------------------------------------------------------------------
 * | Configuration
 * |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Configuration"
    txt_expanded="">

    @can(UserPermission::VIEW_PERFORMANCE_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.performance') }}" :active="request()->routeIs($guard . '.performance')" class=""
            nav_txt="Performance" :default_icon="['src' => 'performance', 'alt' => '']" :active_icon="['src' => 'performance', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_FORM_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($guard . '.form') }}" :active="request()->routeIs($guard . '.form')" class=""
            nav_txt="Forms" :default_icon="['src' => 'forms', 'alt' => '']" :active_icon="['src' => 'forms', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
 * |--------------------------------------------------------------------------
 * | AI Tools
 * |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="AI Tools" txt_expanded="">

    @can(UserPermission::VIEW_MATRIX_PROJECTOR)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Matrix Projector"
            :default_icon="['src' => 'matrix-projector', 'alt' => '']" :active_icon="['src' => 'matrix-projector', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_TALENT_EVALUATOR)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Talent Evaluator"
            :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_PLAN_GENERATOR)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Plan Generator"
            :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>
