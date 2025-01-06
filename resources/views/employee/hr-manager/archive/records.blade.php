@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Archived 201 Records</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/archive.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/dashboard.css'])

@endPushOnce

@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.employees.archive')">
            {{ __('Archived Employee Records') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.employees.archive.records')">
            {{ __('201 Record') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Clark, Avery Mendiola')}}
    </x-slot:heading>

    <x-slot:description>
        <!-- BACK-END REPLACE: Resigned Date & Remaining Time on Retention -->
        <p><span class="fw-bold mb-0">{{ __('Resigned on:') }}</span> June 15, 2021</p>
        <p><span class="fw-bold mb-0">{{ __('Remaining Data Retention Period:') }}</span> 3 years and 195 days</p>
    </x-slot:description>
</x-headings.main-heading>


<div class="col-md-12 d-flex align-items-center">
    @include('components.includes.tab_navs.archived-records-navs')
</div>

<!-- Information Tab Section-->

<!-- BACK-END REPLACE NOTE: Uncomment if table has been set in index -->

<!-- Sub-section: Employee Information -->
<!-- <\\livewire:hr-manager.employees.information :employee="$employee" />
<!-- Sub-section: Documents -->
<!-- <l\\ivewire:hr-manager.employees.documents :employee="$employee" /> -->

<!-- Attendance Tab Section -->
<livewire:hr-manager.archive.attendance />

<!-- Payslips Tab Section -->
<livewire:hr-manager.employees.payslips />

<!-- Contract Tab Section -->
<livewire:hr-manager.archive.contract />

<!-- Leaves Tab Section -->
<livewire:hr-manager.archive.leaves />

<!-- OT Summary Tab Section -->
<livewire:hr-manager.employees.overtime />

<!-- Leaves Tab Section -->
<livewire:hr-manager.archive.evaluations />

<!-- Leaves Tab Section -->
<livewire:hr-manager.archive.issues />
@endsection