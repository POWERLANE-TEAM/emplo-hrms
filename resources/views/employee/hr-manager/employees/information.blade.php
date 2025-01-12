@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title> {{ $employee->last_name }} | Employee Information</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/employee-info.js'])
    @vite(['resources/js/employee/calendar.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/employee-info.css'])
@endPushOnce

@section('content')
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($routePrefix . '.employees.masterlist.all')">
                {{ __('Employee Masterlist') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($routePrefix . '.employees.information')">
                {{ $employee->last_name . __('\'s Information') }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>

    <section class="row pt-2">
        <div class="col-md-4">
            <!-- BACK-END REPLACE: All Employees -->
            <x-form.boxed-selectpicker id="incident_type" :nonce="$nonce" :required="true" :options="['employee_1' => 'Cristian Manalang', 'employee_2' => 'Jobert Owen']"
                placeholder="Select employee">
            </x-form.boxed-selectpicker>
        </div>

        <div class="col-md-8 d-flex align-items-center" wire:ignore>
            @include('components.includes.tab_navs.employees-navs')
        </div>
    </section>

    <section class="mt-3">
        <div class="mt-1 px-3 py-3 w-100">

            <!-- Information Tab Section-->

            <!-- Sub-section: Employee Information -->
            <livewire:hr-manager.employees.information :$employee />
            <!-- Sub-section: Documents -->
            <livewire:hr-manager.employees.documents :$employee />

            <!-- Attendance Tab Section -->
            <livewire:hr-manager.employees.attendance />

            <!-- Payslips Tab Section -->
            <livewire:hr-manager.employees.payslips />

            <!-- Contract Tab Section -->
            <livewire:hr-manager.employees.contract :$routePrefix :$employee />

            <!-- Leaves Tab Section -->
            <livewire:hr-manager.employees.leaves />

            <!-- Overtime Tab Section -->
            <livewire:hr-manager.employees.overtime />
        </div>
    </section>
@endsection
