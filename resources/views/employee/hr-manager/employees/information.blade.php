@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Employee's Training Records</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])

@endPushOnce
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="'#'"> <!-- REPLACE: Link to the All Training Records table -->
            Employees
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.employees.information')">
            Employee Information
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<section class="row pt-4">
    <label class="ps-3 mb-2 fw-semibold text-primary fs-5"> Employee Name </label>

    <div class="col-md-4">
        <!-- BACK-END REPLACE: All Employees -->
        <x-form.boxed-selectpicker id="incident_type" :nonce="$nonce" :required="true" :options="['employee_1' => 'Cristian Manalang', 'employee_2' => 'Jobert Owen']" placeholder="Select employee">
        </x-form.boxed-selectpicker>
    </div>

    <div class="col-md-8 d-flex align-items-center">
        @include('components.includes.tab_navs.employees-navs')
    </div>
</section>

<section class="mt-3">
    <div class="mt-1 px-3 py-4 w-100">

        <!-- Information Tab Section-->
        <livewire:hr-manager.employees.information />

        <!-- Attendance Tab Section -->
        <livewire:hr-manager.employees.attendance />

        <!-- Payslips Tab Section -->
        <livewire:hr-manager.employees.payslips />

        <!-- Contract Tab Section -->
        <livewire:hr-manager.employees.contract />

        <!-- Leaves Tab Section -->
        <livewire:hr-manager.employees.leaves />

        <!-- Overtime Tab Section -->
        <livewire:hr-manager.employees.overtime />
    </div>
</section>

@endsection