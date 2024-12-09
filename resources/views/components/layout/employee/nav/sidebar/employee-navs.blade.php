@aware(['iconSize' => '31px', 'iconRatio' => '1/1', 'user'])
@use('App\Enums\UserRole')
@use('App\Enums\UserPermission')

{{--
* |--------------------------------------------------------------------------
* | Main Menu
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Main"
    txt_expanded="Menu">

    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([
        UserPermission::VIEW_EMPLOYEE_DASHBOARD,
        UserPermission::VIEW_HR_MANAGER_DASHBOARD,
        UserPermission::VIEW_ADMIN_DASHBOARD
    ])
    <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.dashboard') }}"
        :active="request()->routeIs($routePrefix . '.dashboard')" class="tw-order-[0]" nav_txt="Dashboard"
        :defaultIcon="['src' => 'dashboard', 'alt' => '']" :activeIcon="['src' => 'dashboard', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $navAttendanceOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE) ? 4 : 2;
        $navAttendanceRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE)
            ? $routePrefix . '.attendance.index'
            : $routePrefix . '.attendance.index'; /* $routePrefix . '.attendance.show' */
    @endphp

    @canAny([UserPermission::VIEW_DAILY_ATTENDANCE, UserPermission::VIEW_ALL_DAILY_ATTENDANCE])
    <x-layout.employee.nav.sidebar.nav-item :href="route($navAttendanceRoute)"
        :active="request()->routeIs($navAttendanceRoute)" class="tw-order-[{{ $navAttendanceOrder }}]"
        nav_txt="Attendance" :defaultIcon="['src' => 'attendance', 'alt' => '']" :activeIcon="['src' => 'attendance', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $navPayslipOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_PAYSLIPS) ? 7 : 3;
    @endphp
    @canAny([UserPermission::VIEW_PAYSLIPS, UserPermission::VIEW_ALL_PAYSLIPS])
    <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.hr.payslips.all') }}"
        :active="request()->routeIs($routePrefix . '.hr.payslips.all')" class="tw-order-[{{ $navPayslipOrder }}]"
        nav_txt="Payslips" :defaultIcon="['src' => 'payslips', 'alt' => '']" :activeIcon="['src' => 'payslips', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    <!-- @php
        $navPerformanceOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL) ? 8 : 4;
    @endphp
    @canAny([UserPermission::VIEW_EMP_PERFORMANCE_EVAL, UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL])
    <x-layout.employee.nav.sidebar.nav-item :href="route($routePrefix . '.performance.evaluation.index', ['employeeStatus' => 'probationary'])" :active="request()->routeIs($routePrefix . '.performance.evaluation.index')" class="tw-order-[{{ $navPerformanceOrder }}]" nav_txt="Performance"
        :defaultIcon="['src' => 'performances', 'alt' => '']" :activeIcon="['src' => 'performances', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan -->

    {{-- HR Manager / HR Staff --}}
    @can(UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL)
        <x-layout.employee.nav.sidebar.nested-nav-items nav_txt="Performance" :active="request()->routeIs($routePrefix . 'evaluations.*')" class="tw-order-[11]" :defaultIcon="['src' => 'performances', 'alt' => 'Performance']"
            :activeIcon="['src' => 'performances', 'alt' => 'Relations']" :children="[
                ['href' => route($routePrefix . '.hr.evaluation-results.probationary.all'), 'active' => request()->routeIs($routePrefix . '.hr.evaluation-results.probationary.all'), 'nav_txt' => 'Probationary'],
                ['href' => route($routePrefix . '.hr.evaluation-results.regular.all'), 'active' => request()->routeIs($routePrefix . '.hr.evaluation-results.regular.all'), 'nav_txt' => 'Regular'],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- Employee --}}
    @canAny([UserPermission::VIEW_EMP_PERFORMANCE_EVAL])
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.leaves')"
        class="tw-order-[4]" nav_txt="Performance" :defaultIcon="['src' => 'performances', 'alt' => '']"
        :activeIcon="['src' => 'performances', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $navLeavesOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES) ? 5 : 3; // Adjust order as needed
        $navLeavesRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES)
            ? $routePrefix . '.hr.leaves.all'
            : $routePrefix . '.general.leaves.all'; /* Adjust routes as required */
    @endphp

    @canAny([UserPermission::VIEW_LEAVES, UserPermission::VIEW_ALL_LEAVES])
    <x-layout.employee.nav.sidebar.nav-item :href="route($navLeavesRoute)" :active="request()->routeIs($navLeavesRoute)"
        class="tw-order-[{{ $navLeavesOrder }}]" nav_txt="Leaves" :defaultIcon="['src' => 'leaves', 'alt' => '']"
        :activeIcon="['src' => 'leaves', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $navOvertimeOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME) ? 6 : 4; // Adjust order as needed
        $navOvertimeRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME)
            ? $routePrefix . '.hr.overtime.all'
            : $routePrefix . '.general.overtime.all'; /* Adjust routes as required */
    @endphp

    @canAny([UserPermission::VIEW_OVERTIME, UserPermission::VIEW_ALL_OVERTIME])
    <x-layout.employee.nav.sidebar.nav-item :href="route($navOvertimeRoute)"
        :active="request()->routeIs($navOvertimeRoute)" class="tw-order-[{{ $navOvertimeOrder }}]" nav_txt="Overtime"
        :defaultIcon="['src' => 'overtime', 'alt' => '']" :activeIcon="['src' => 'overtime', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan


    {{-- Employee, Supervisor --}}
    @canAny([UserPermission::VIEW_DOCUMENTS])
    <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->routeIs($routePrefix . '.documents')"
        class="tw-order-[7]" nav_txt="Documents" :defaultIcon="['src' => 'documents', 'alt' => '']" :activeIcon="['src' => 'documents', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, Supervisor --}}
    @can(UserPermission::VIEW_ISSUES)
        <x-layout.employee.nav.sidebar.nav-item href="#" {{-- :active="request()->routeIs($routePrefix . '.issues')" --}}
            class="tw-order-[8]" nav_txt="Issues" :defaultIcon="['src' => 'issues', 'alt' => '']" :activeIcon="['src' => 'issues', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_PENDING_APPLICATIONS)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.applications', ['applicationStatus' => 'pending']) }}"
            :active="request()->routeIs($routePrefix . '.applications', ['applicationStatus' => 'pending'])"
            class="tw-order-[2]" nav_txt="Applicants" :defaultIcon="['src' => 'applicants', 'alt' => '']"
            :activeIcon="['src' => 'applicants', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_EMPLOYEES)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.index') }}"
            :active="request()->routeIs($routePrefix . '.employees')" class="tw-order-[3]" nav_txt="Employees"
            :defaultIcon="['src' => 'employee', 'alt' => '']" :activeIcon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_RELATIONS)
        <x-layout.employee.nav.sidebar.nested-nav-items nav_txt="Relations" :active="request()->routeIs($routePrefix . 'relations.*')" class="tw-order-[11]" :defaultIcon="['src' => 'relations', 'alt' => 'Relations']"
            :activeIcon="['src' => 'relations', 'alt' => 'Relations']" :children="[
                ['href' => route($routePrefix . '.hr.relations.incidents.all'), 'active' => request()->routeIs($routePrefix . '.hr.relations.incidents.all'), 'nav_txt' => 'Incidents'],
                ['href' => route($routePrefix . '.hr.relations.issues.all'), 'active' => request()->routeIs($routePrefix . '.hr.relations.issues.all'), 'nav_txt' => 'Issues'],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_TRAINING)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.training.all') }}"
            :active="request()->routeIs($routePrefix . '.training.all')" class="tw-order-[3]" nav_txt="Training"
            :defaultIcon="['src' => 'training', 'alt' => '']" :activeIcon="['src' => 'training', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_ALL_ACCOUNTS)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.accounts.index') }}"
            :active="request()->routeIs($routePrefix . '.accounts.index')" class="" nav_txt="Accounts"
            :defaultIcon="['src' => 'accounts', 'alt' => '']" :activeIcon="['src' => 'accounts', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_EMPLOYEE_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.job-family.create') }}"
            :active="request()->routeIs([$routePrefix . '.job-family.create', $routePrefix . '.job-title.create'])" class=""
            nav_txt="Employees" :defaultIcon="['src' => 'employee', 'alt' => '']" :activeIcon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_CALENDAR_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.calendar') }}"
            :active="request()->routeIs($routePrefix . '.calendar')" class="" nav_txt="Calendar" :defaultIcon="['src' => 'calendar-manager', 'alt' => '']" :activeIcon="['src' => 'calendar', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_JOB_LISTING_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.job-board.create') }}"
            :active="request()->routeIs($routePrefix . '.job-board.create')" class="" nav_txt="Job Board"
            :defaultIcon="['src' => 'jobboard', 'alt' => '']" :activeIcon="['src' => 'job-listing', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_ANNOUNCEMENT_MANAGER)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.announcement.create') }}"
            :active="request()->routeIs($routePrefix . '.announcement.create')" class="" nav_txt="Announcements"
            :defaultIcon="['src' => 'announcements', 'alt' => '']" :activeIcon="['src' => 'announcements', 'alt' => '']">
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
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.config.performance.categories') }}"
            :active="request()->routeIs($routePrefix . '.config.performance.*')" class="" nav_txt="Performance"
            :defaultIcon="['src' => 'performances', 'alt' => '']" :activeIcon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_FORM_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.config.form.pre-employment') }}"
            :active="request()->routeIs($routePrefix . '.config.form.*')" class="" nav_txt="Forms" :defaultIcon="['src' => 'forms', 'alt' => '']" :activeIcon="['src' => 'forms', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
* |--------------------------------------------------------------------------
* | AI Tools
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="AI Tools"
    txt_expanded="">

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_TALENT_EVALUATOR)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.resume-evaluator.rankings') }}"
            :active="request()->routeIs($routePrefix . '.resume-evaluator.rankings')" class="" nav_txt="Resume Evaluator"
            :defaultIcon="['src' => 'resume-evaluator', 'alt' => '']" :activeIcon="['src' => 'resume-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_PLAN_GENERATOR)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Plan Generator"
            :defaultIcon="['src' => 'plan-generator', 'alt' => '']" :activeIcon="['src' => 'plan-generator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>