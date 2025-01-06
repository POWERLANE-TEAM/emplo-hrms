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

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Archived Employee 201 Records')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('View and track resigned employees\' archived 201 records.') }}</p>
    </x-slot:description>
</x-headings.main-heading>

<!-- BACK-END REPLACE: TABLE OF ALL RESIGNED EMPLOYEES -->

<section class="row pt-2">
    <div class="col-md-4 d-flex align-items-center">
        <!-- BACK-END REPLACE: All Employees -->
        <x-form.boxed-selectpicker id="employee" :nonce="$nonce" :required="true" :options="['employee_1' => 'Cristian Manalang', 'employee_2' => 'Jobert Owen']" placeholder="Select employee">
        </x-form.boxed-selectpicker>
    </div>

    <div class="col-md-8 d-flex align-items-center">
        @include('components.includes.tab_navs.archived-records')
    </div>
</section>

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