@aware(['iconSize' => '31px', 'iconRatio' => '1/1', 'user'])
@use('App\Enums\UserRole')
@use('App\Enums\UserPermission')

{{-- DO NOT AUTO FORMAT! --}}

{{--
* |--------------------------------------------------------------------------
* | BLOCK: Helpers
* |--------------------------------------------------------------------------
--}}

@php

    /**
     * HR Manager: Attendance
     */

    $navAttendanceOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE) ? 4 : 2;
    $navAttendanceRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE)
        ? $routePrefix . '.attendance.index'
        : $routePrefix . '.attendance.show';


    /**
     * Payslip
     */

    $navPayslipOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_PAYSLIPS) ? 7 : 3;

    $navPayslipRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_PAYSLIPS)
        ? $routePrefix . '.payslips.general'
        : $routePrefix . '.payslips.index';

    $navPayslipActivePattern = $user->hasPermissionTo(UserPermission::VIEW_ALL_PAYSLIPS)
        ? $routePrefix . '.payslips.*'
        : $routePrefix . '.payslips.*';


    /**
     * Overtime
     */
    $navOvertimeOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME_REQUEST) ? 6 : 4; // Adjust order as needed
    $navOvertimeRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME_REQUEST)
        ? [$routePrefix . '.overtimes.requests.summaries']
        : [
            $routePrefix . '.overtimes.index',
            $routePrefix . '.overtimes.summaries',
            $routePrefix . '.overtimes.recents',
            $routePrefix . '.overtimes.archive',
        ];

    /**
     * Leaves
     */
    $navLeavesOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES) ? 5 : 3; // Adjust order as needed
    $navLeavesRoute = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES)
        ? $routePrefix . '.leaves.requests.general'
        : $routePrefix . '.leaves.index';
    $navLeavesActivePattern = $user->hasPermissionTo(UserPermission::VIEW_ALL_LEAVES)
        ? $routePrefix . '.leaves.*'
        : $routePrefix . '.leaves.*';


@endphp


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
    <x-layout.employee.nav.sidebar.nav-item
        href="{{ route($routePrefix . '.dashboard') }}"
        :active="request()->routeIs($routePrefix . '.dashboard')"
        class="order-first" nav_txt="Dashboard"
        :defaultIcon="['src' => 'dashboard', 'alt' => '']" :activeIcon="['src' => 'dashboard', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}

    @canAny([UserPermission::VIEW_ALL_DAILY_ATTENDANCE])
    <x-layout.employee.nav.sidebar.nested-nav-items

        :href="!$user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE) ? route($navAttendanceRoute) : null"

        :active="request()->routeIs($navAttendanceRoute, when(!$user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE), ['range' => 'daily']))"
        class="order-{{ $navAttendanceOrder }}"
        nav_txt="Attendance"
        :defaultIcon="['src' => 'attendance', 'alt' => '']"
        :activeIcon="['src' => 'attendance', 'alt' => '']"
        :children="when( $user->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE), [
            ['href' => route($navAttendanceRoute, ['range' => 'daily']), 'active' => request()->routeIs($navAttendanceRoute, ['range' => 'daily']), 'nav_txt' => 'Daily Time Log', 'range' => 'daily'],
            ['href' => route($navAttendanceRoute, ['range' => 'period']), 'active' => request()->routeIs($navAttendanceRoute, ['range' => 'period']), 'nav_txt' => 'Attendance Records', 'range' => 'period']
        ])"

        :isActiveClosure="function($isActive, $child) use ($routePrefix, $navAttendanceRoute) {
            return request()->routeIs( $navAttendanceRoute) && request()->route('range') === $child['range'];
        }"

        >
    </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    @can(UserPermission::VIEW_EMP_PERFORMANCE_TRAINING)
    {{-- Regular Employee --}}
    <x-layout.employee.nav.sidebar.nav-item
        href="{{ route($routePrefix . '.trainings.index') }}"
        :active="request()->routeIs($routePrefix . '.trainings.index*')"
        class="order-5" nav_txt="Trainings"
        :defaultIcon="['src' => 'training', 'alt' => '']"
        :activeIcon="['src' => 'training', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan


    {{-- Employee, HR Manager, Supervisor --}}
    @canAny([UserPermission::VIEW_PAYSLIPS, UserPermission::VIEW_ALL_PAYSLIPS])
    <x-layout.employee.nav.sidebar.nav-item
        href="{{ route($navPayslipRoute) }}"
        :active="request()->routeIs($navPayslipActivePattern)"
        class="order-{{ $navPayslipOrder }}"
        nav_txt="Payslips"
        :defaultIcon="['src' => 'payslips', 'alt' => '']"
        :activeIcon="['src' => 'payslips', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, HR Manager, Supervisor --}}
    @php
        $navPerformanceOrder = $user->hasPermissionTo(UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL) ? 10 : 4;
    @endphp
    {{-- @canAny([UserPermission::VIEW_EMP_PERFORMANCE_EVAL, UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL])
    <x-layout.employee.nav.sidebar.nav-item :href="route($routePrefix . '.performance.evaluation.index', ['employeeStatus' => 'probationary'])" :active="request()->routeIs($routePrefix . '.performance.evaluation.index')" class="order-{{ $navPerformanceOrder }}" nav_txt="Performance"
        :defaultIcon="['src' => 'performances', 'alt' => '']" :activeIcon="['src' => 'performances', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan --> --}}

    {{-- HR Manager / HR Staff --}}
    @can(UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL)
    <x-layout.employee.nav.sidebar.nested-nav-items
        nav_txt="Performance"
        :active="request()->routeIs($routePrefix . '.performance.evaluation.index')"
        class="order-10"
        :defaultIcon="['src' => 'performances', 'alt' => 'Performance']"

        :activeIcon="['src' => 'performances', 'alt' => 'Relations']" :children="[
            ['href' => route($routePrefix . '.performances.probationaries.general'), 'active' => request()->routeIs($routePrefix . '.performances.probationaries.general'), 'nav_txt' => 'Probationary - All'],
            ['href' => route($routePrefix . '.performances.regulars.general'), 'active' => request()->routeIs($routePrefix . '.performances.regulars.general'), 'nav_txt' => 'Regular - All'],]">

    </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- Employee --}}
    @canAny([UserPermission::VIEW_EMP_PERFORMANCE_EVAL])
    <x-layout.employee.nav.sidebar.nav-item
        :href="route($routePrefix.'.performances.index')"
        :active="request()->routeIs([$routePrefix.'.performances.regular', $routePrefix.'.performances.probationary'])"
        class="order-4"
        nav_txt="Performance"
        :defaultIcon="['src' => 'performances', 'alt' => '']"
        :activeIcon="['src' => 'performances', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @canAny([UserPermission::VIEW_EMP_PERFORMANCE_EVAL])
    <x-layout.employee.nav.sidebar.nav-item
        href="{{ route($routePrefix . '.attendance') }}"
        :active="request()->routeIs($routePrefix . '.attendance')"
        class="order-2"
        nav_txt="Attendance"
        :defaultIcon="['src' => 'attendance', 'alt' => '']"
        :activeIcon="['src' => 'attendance', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @canAny([UserPermission::VIEW_LEAVES, UserPermission::VIEW_ALL_LEAVES])
    <x-layout.employee.nav.sidebar.nav-item :href="route($navLeavesRoute)"
        :active="request()->routeIs($routePrefix . '.leaves.*') && !request()->routeIs($routePrefix . '.leaves.requests')"
        class="order-{{ $navLeavesOrder }}"
        nav_txt="Leaves"
        :defaultIcon="['src' => 'leaves', 'alt' => '']"
        :activeIcon="['src' => 'leaves', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @canAny([
        UserPermission::VIEW_OVERTIME,
        UserPermission::VIEW_SUBORDINATE_OVERTIME_REQUEST,
        UserPermission::VIEW_ALL_SUBORDINATE_LEAVE_REQUEST,
        UserPermission::VIEW_ALL_OVERTIME_REQUEST,
    ])
    <x-layout.employee.nav.sidebar.nav-item
        :href="route($navOvertimeRoute[0])"
        :active="request()->routeIs($routePrefix . '.overtimes.*')"
        class="order-{{ $navOvertimeOrder }}"
        nav_txt="Overtime"
        :defaultIcon="['src' => 'overtime', 'alt' => '']"
        :activeIcon="['src' => 'overtime', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcanAny


    {{-- Employee, Supervisor --}}
    @canAny([UserPermission::VIEW_DOCUMENTS])
    <x-layout.employee.nav.sidebar.nav-item
        href="{{ route($routePrefix . '.files.contracts') }}"
        :active="request()->routeIs($routePrefix . '.files.*')"
        class="order-7"
        nav_txt="Documents"
        :defaultIcon="['src' => 'documents', 'alt' => '']"
        :activeIcon="['src' => 'documents', 'alt' => '']">
    </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Employee, Supervisor --}}
    @can(UserPermission::VIEW_ISSUES)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.relations.issues.index') }}"
            :active="request()->routeIs($routePrefix . '.relations.issues.*')"
            class="order-8"
            nav_txt="Issues"
            :defaultIcon="['src' => 'issues', 'alt' => '']"
            :activeIcon="['src' => 'issues', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_RESIGNATION)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.separation.index') }}"
            :active="request()->routeIs($routePrefix . '.separation.*')"
            class="order-12"
            nav_txt="Separation"
            :defaultIcon="['src' => 'separation', 'alt' => '']"
            :activeIcon="['src' => 'separation', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_PENDING_APPLICATIONS)
        <x-layout.employee.nav.sidebar.nested-nav-items
            :active="request()->routeIs($routePrefix . '.applications', ['applicationStatus' => 'pending'])"
            class="order-2"
            nav_txt="Applicants"
            :defaultIcon="['src' => 'applicants', 'alt' => '']"
            :activeIcon="['src' => 'applicants', 'alt' => '']"
            :children="[
                ['href' => route($routePrefix . '.applications', ['applicationStatus' => 'pending']), 'active' => request()->routeIs($routePrefix . '.applications', ['applicationStatus' => 'pending']), 'nav_txt' => 'Pending', 'applicationStatus' => 'pending'],
                ['href' => route($routePrefix . '.applications', ['applicationStatus' => 'qualified']), 'active' => request()->routeIs($routePrefix . '.applications', ['applicationStatus' => 'qualified']), 'nav_txt' => 'Qualified', 'applicationStatus' => 'qualified'],
                // ['href' => route($routePrefix . '.applications', ['applicationStatus' => 'preemployed']), 'active' => request()->routeIs($routePrefix . '.applications', ['applicationStatus' => 'preemployed']), 'nav_txt' => 'Pre-employed', 'applicationStatus' => 'preemployed'],
            ]"

        :isActiveClosure="function($isActive, $child) use ($routePrefix) {
            return request()->routeIs($routePrefix . '.applications') && request()->route('applicationStatus') === $child['applicationStatus'];
        }"

            >
        </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_EMPLOYEES, UserPermission::VIEW_ARCHIVED_EMP_201_FILES)
        <x-layout.employee.nav.sidebar.nested-nav-items
            nav_txt="Employees"
            :active="request()->routeIs($routePrefix . '.employees.*')"
            class="order-3"
            :defaultIcon="['src' => 'employee', 'alt' => 'Relations']"
            :activeIcon="['src' => 'employee', 'alt' => 'Relations']"
            :children="[
                [
                    'href' => route($routePrefix . '.employees.masterlist.all'),
                    'active' => request()->routeIs($routePrefix . '.employees.masterlist.*'),
                    'nav_txt' => 'Employees'
                ],
                [
                    'href' => route($routePrefix . '.archives.index'),
                    'active' => request()->routeIs($routePrefix . '.archives.*'),
                    'nav_txt' => 'Archived 201 Records'
                ],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>

    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_RELATIONS)
        <x-layout.employee.nav.sidebar.nested-nav-items
            nav_txt="Relations"
            :active="request()->routeIs($routePrefix . '.relations.*')"
            class="order-12"
            :defaultIcon="['src' => 'relations', 'alt' => 'Relations']"
            :activeIcon="['src' => 'relations', 'alt' => 'Relations']"
            :children="[
                [
                    'href' => route($routePrefix . '.relations.incidents.index'),
                    'active' => request()->routeIs($routePrefix . '.relations.incidents*'),
                    'nav_txt' => 'Incidents'
                ],
                [
                    'href' => route($routePrefix . '.relations.issues.general'),
                    'active' => request()->routeIs($routePrefix . '.relations.issues*'),
                    'nav_txt' => 'Issues'
                ],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_TRAININGS)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.trainings.general') }}"
            :active="request()->routeIs($routePrefix . '.trainings.general.*')"
            class="order-11" nav_txt="Trainings"
            :defaultIcon="['src' => 'training', 'alt' => '']"
            :activeIcon="['src' => 'training', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_ALL_FILED_RESIGNATION_LETTERS)
    <x-layout.employee.nav.sidebar.nested-nav-items
        nav_txt="Separation"
        :active="request()->routeIs($routePrefix . '.separation.*')"
        class="order-12"
        :defaultIcon="['src' => 'separation', 'alt' => 'Separation']"
        :activeIcon="['src' => 'separation', 'alt' => 'Separation']" :children="[
            ['href' => route($routePrefix . '.separation.resignations'), 'active' => request()->routeIs($routePrefix . '.separation.resignations*'), 'nav_txt' => 'Resignations'],
            ['href' => route($routePrefix . '.separation.coe'), 'active' => request()->routeIs($routePrefix . '.separation.coe*'), 'nav_txt' => 'COEs'],]">
    </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_REPORTS)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.reports') }}"
            :active="request()->routeIs($routePrefix . '.reports')"
            class="order-13" nav_txt="Reports"
            :defaultIcon="['src' => 'reports', 'alt' => '']"
            :activeIcon="['src' => 'reports', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_ALL_ACCOUNTS)
        <x-layout.employee.nav.sidebar.nested-nav-items
            nav_txt="Accounts"
            :active="request()->routeIs($routePrefix . 'accounts.*')"
            class="order-11"
            :defaultIcon="['src' => 'accounts', 'alt' => 'Accounts']"
            :activeIcon="['src' => 'accounts', 'alt' => 'Relations']"
            :children="[
                ['href' => route($routePrefix . '.accounts.index'), 'active' => request()->routeIs($routePrefix . '.accounts.index'), 'nav_txt' => 'List'],
                ['href' => route($routePrefix . '.accounts.create'), 'active' => request()->routeIs($routePrefix . '.accounts.create'), 'nav_txt' => 'Add New'],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan


    {{-- Head Admin --}}
    @can(UserPermission::VIEW_EMPLOYEE_MANAGER)
        @if($routePrefix === 'admin')
            <x-layout.employee.nav.sidebar.nav-item
                href="{{ route($routePrefix . '.job-family.index') }}"
                :active="request()->routeIs([$routePrefix . '.job-family.*', $routePrefix . '.job-titles.*'])"
                class=""
                nav_txt="Organization"
                :defaultIcon="['src' => 'employee', 'alt' => '']"
                :activeIcon="['src' => 'employee', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endif
    @endcan

    {{-- Head Admin --}}
    <!-- @can(UserPermission::VIEW_CALENDAR_MANAGER)
        @if($routePrefix === 'admin')
            <x-layout.employee.nav.sidebar.nav-item
                href="{{ route($routePrefix . '.calendar.monthly') }}"
                :active="request()->routeIs($routePrefix . '.calendar.*')"
                class=""
                nav_txt="Calendar"
                :defaultIcon="['src' => 'calendar-manager', 'alt' => '']"
                :activeIcon="['src' => 'calendar', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endif
    @endcan -->

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_JOB_LISTING_MANAGER)
        @if($routePrefix === 'admin')
            <x-layout.employee.nav.sidebar.nav-item
                href="{{ route($routePrefix . '.job-board.create') }}"
                :active="request()->routeIs($routePrefix . '.job-board.create')"
                class=""
                nav_txt="Job Board"
                :defaultIcon="['src' => 'jobboard', 'alt' => '']"
                :activeIcon="['src' => 'job-listing', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endif
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_ANNOUNCEMENT_MANAGER)
        @if($routePrefix === 'admin')
            <x-layout.employee.nav.sidebar.nav-item
                href="{{ route($routePrefix . '.announcements.index') }}"
                :active="request()->routeIs($routePrefix . '.announcements.*')"
                class=""
                nav_txt="Announcements"
                :defaultIcon="['src' => 'announcements', 'alt' => '']"
                :activeIcon="['src' => 'announcements', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endif
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
* |--------------------------------------------------------------------------
* | ADMINISTRATION
* |--------------------------------------------------------------------------
--}}

@can(UserPermission::VIEW_ADMINISTRATION_SECTION)
<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Admin"
    txt_expanded="Administration">

    @can(UserPermission::VIEW_EMPLOYEE_MANAGER, UserPermission::VIEW_JOB_LISTING_MANAGER)
        <x-layout.employee.nav.sidebar.nested-nav-items
            nav_txt="Organization"
            :active="request()->routeIs([$routePrefix . '.job-family.*', $routePrefix . '.job-titles.*'])"
            class=""
            :defaultIcon="['src' => 'jobboard', 'alt' => 'Oragnization']"
            :activeIcon="['src' => 'jobboard', 'alt' => 'Oragnization']"
            :children="[
                [
                    'href' => route($routePrefix . '.job-family.index'),
                    'active' => [$routePrefix . '.job-family.*', $routePrefix . '.job-titles.*'],
                    'nav_txt' => 'Job Families & Titles'
                ],
                [
                    'href' => route($routePrefix . '.job-board.create'),
                    'active' => request()->routeIs($routePrefix . '.job-board.create'),
                    'nav_txt' => 'Job Board'
                ],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>

        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.announcements.index') }}"
            :active="request()->routeIs($routePrefix . '.announcements.*')"
            class=""
            nav_txt="Announcements"
            :defaultIcon="['src' => 'announcements', 'alt' => '']"
            :activeIcon="['src' => 'announcements', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>

        <!-- <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.calendar.monthly') }}"
            :active="request()->routeIs($routePrefix . '.calendar.*')"
            class=""
            nav_txt="Calendar"
            :defaultIcon="['src' => 'calendar-manager', 'alt' => '']"
            :activeIcon="['src' => 'calendar', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item> -->

    @endcan

</x-layout.employee.nav.sidebar.nav-group>
@endcan

{{--
* |--------------------------------------------------------------------------
* | Managerial
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="Manage"
    txt_expanded="Managerial">

    {{-- Supervisor / Head Dept --}}
    @can(UserPermission::VIEW_ALL_SUBORDINATE_REQUESTS)
        <x-layout.employee.nav.sidebar.nested-nav-items
            nav_txt="Requests"
            :active="request()->routeIs($routePrefix . 'requests.*')"
            :defaultIcon="['src' => 'requests', 'alt' => 'Performance']"
            :activeIcon="['src' => 'requests', 'alt' => 'Relations']" :children="[
                ['href' => route($routePrefix . '.leaves.requests'), 'active' => request()->routeIs($routePrefix . '.leaves.requests'), 'nav_txt' => 'Leaves'],
                ['href' => route($routePrefix . '.overtimes.requests.cut-offs'), 'active' => request()->routeIs($routePrefix . '.overtimes.requests.cut-offs'), 'nav_txt' => 'Overtime'],
            ]">
        </x-layout.employee.nav.sidebar.nested-nav-items>
    @endcan

    {{-- Supervisor / Head Dept --}}
    @can(UserPermission::VIEW_ALL_SUBORDINATE_OVERTIME_SUMMARY_FORMS)
        <x-layout.employee.nav.sidebar.nav-item href="{{ route($routePrefix . '.overtimes.requests.summaries') }}"
            :active="request()->routeIs([$routePrefix . '.overtimes.requests.summaries', $routePrefix . '.overtimes.requests.employee.summaries'])"
            class="" nav_txt="OT Summary Forms"
            :defaultIcon="['src' => 'ot-summary-form', 'alt' => '']"
            :activeIcon="['src' => 'ot-summary-form', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Supervisor / Head Dept --}}
    @can(UserPermission::VIEW_ALL_SUBORDINATE_PERFORMANCE_EVAL_FORM)
        <x-layout.employee.nav.sidebar.nested-nav-items
            nav_txt="Evaluations"
            :active="request()->routeIs([$routePrefix.'.performance.regulars.*', $routePrefix.'performance.probationaries.*'])"
            class="order-10"
            :defaultIcon="['src' => 'evaluations', 'alt' => 'performance evaluations icon']"
            :activeIcon="['src' => 'evaluations', 'alt' => 'performance evaluations icon']" :children="[
                ['href' => route($routePrefix .'.performances.probationaries.index'), 'active' => request()->routeIs($routePrefix . '.performances.probationaries.index'), 'nav_txt' => 'Probationary'],
                ['href' => route($routePrefix .'.performances.regulars.index'), 'active' => request()->routeIs($routePrefix . '.performances.regulars.index'), 'nav_txt' => 'Regular'],]">
        </x-layout.employee.nav.sidebar.nested-nav-items>
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
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.config.performance.categories') }}"
            :active="request()->routeIs($routePrefix . '.config.performance.*')"
            class=""
            nav_txt="Performance"
            :defaultIcon="['src' => 'performances', 'alt' => '']"
            :activeIcon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- Head Admin --}}
    @can(UserPermission::VIEW_FORM_CONFIG)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.config.form.pre-employment') }}"
            :active="request()->routeIs($routePrefix . '.config.form.*')"
            class="" nav_txt="Forms"
            :defaultIcon="['src' => 'forms', 'alt' => '']"
            :activeIcon="['src' => 'forms', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>


{{--
* |--------------------------------------------------------------------------
* | AI Tools
* |--------------------------------------------------------------------------
--}}

<x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="AI"
    txt_expanded="AI Tools">

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_TALENT_EVALUATOR)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.resume-evaluator.rankings') }}"
            :active="request()->routeIs($routePrefix . '.resume-evaluator.rankings')"
            class=""
            nav_txt="Resume Evaluator"
            :defaultIcon="['src' => 'resume-evaluator', 'alt' => '']"
            :activeIcon="['src' => 'resume-evaluator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    {{-- HR Manager --}}
    @can(UserPermission::VIEW_PLAN_GENERATOR)
        <x-layout.employee.nav.sidebar.nav-item
            href="{{ route($routePrefix . '.performances.plan.improvement.index') }}"
            :active="request()->routeIs($routePrefix . '.performances.plan.improvement.index')"
            class=""
            nav_txt="Plan Generator"
            :defaultIcon="['src' => 'plan-generator', 'alt' => '']" :activeIcon="['src' => 'plan-generator', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

</x-layout.employee.nav.sidebar.nav-group>
