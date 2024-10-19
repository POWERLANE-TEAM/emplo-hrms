<?php
/**
 * This component renders the sidebar navigations for HR Managers.
 *
 * Props:
 * @property string $include Determines which menu to include ('main-menu' or 'ai-tools').
 *
 * Aware:
 * @property string $icon_size The size of the icons.
 * @property string $icon_ratio The ratio of the icons.
 */
?>

@props(['include' => 'main-menu'])
@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])
@use('App\Enums\UserPermission')


{{-- {{ dump($user) }} --}}
@php
    $authenticated_user = Auth::user();
    // dump($authenticated_user);
@endphp


@if ($include == 'main-menu')
    @can(UserPermission::VIEW_HR_MANAGER_DASHBOARD)
        <x-layout.employee.nav.sidebar.nav-item href="dashboard" :active="request()->is('employee/dashboard')" class="" nav_txt="Dashboard"
            :default_icon="['src' => 'dashboard', 'alt' => '']" :active_icon="['src' => 'dashboard', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_APPLICANTS)
        <x-layout.employee.nav.sidebar.nav-item href="applicants" :active="request()->is('employee/applicants')" class="" nav_txt="Applicants"
            :default_icon="['src' => 'applicants', 'alt' => '']" :active_icon="['src' => 'applicants', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_EMPLOYEES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Employees"
            :default_icon="['src' => 'employee', 'alt' => '']" :active_icon="['src' => 'employee', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_ATTENDANCE)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Attendance"
            :default_icon="['src' => 'attendance', 'alt' => '']" :active_icon="['src' => 'attendance', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_LEAVES)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Leaves"
            :default_icon="['src' => 'leaves', 'alt' => '']" :active_icon="['src' => 'leaves', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_OVERTIME)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Overtime"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_PAYSLIPS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Payslips"
            :default_icon="['src' => 'clients', 'alt' => '']" :active_icon="['src' => 'clients', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_PERFORMANCE)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Performance"
            :default_icon="['src' => 'performances', 'alt' => '']" :active_icon="['src' => 'performances', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan

    @can(UserPermission::VIEW_ALL_RELATIONS)
        <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class="" nav_txt="Relations"
            :default_icon="['src' => 'daily', 'alt' => '']" :active_icon="['src' => 'daily', 'alt' => '']">
        </x-layout.employee.nav.sidebar.nav-item>
    @endcan
@endif

@if ($include == 'ai-tools')
    <x-layout.employee.nav.sidebar.nav-group :sidebar_expanded="$sidebar_expanded" class="" txt_collapsed="AI Tools" txt_expanded="">

        @can(UserPermission::VIEW_MATRIX_PROJECTOR)
            <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                nav_txt="Matrix Projector" :default_icon="['src' => 'matrix-projector', 'alt' => '']" :active_icon="['src' => 'matrix-projector', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endcan

        @can(UserPermission::VIEW_TALENT_EVALUATOR)
            <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                nav_txt="Talent Evaluator" :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endcan

        @can(UserPermission::VIEW_PLAN_GENERATOR)
            <x-layout.employee.nav.sidebar.nav-item href="#" :active="request()->is('#')" class=""
                nav_txt="Plan Generator" :default_icon="['src' => 'skill-evaluator', 'alt' => '']" :active_icon="['src' => 'skill-evaluator', 'alt' => '']">
            </x-layout.employee.nav.sidebar.nav-item>
        @endcan

    </x-layout.employee.nav.sidebar.nav-group>
@endif
