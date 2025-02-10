@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use(Illuminate\Support\Carbon)
@use(App\Enums\EmploymentStatus)

@section('head')
<title>{{ $employee->full_name }} • Archived 201 Records</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/archive.js'])
    @vite(['resources/js/employee/calendar.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/dashboard.css'])
@endPushOnce

@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.archives.index')">
            {{ __('Archived Employee Records') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.archives.employee')">
            {{ __("{$employee->last_name}'s 201 Record") }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

@php
    $separationDate = Carbon::parse($employee->lifecycle->separated_at);
    $retentionPeriod = EmploymentStatus::separatedEmployeeDataRetentionPeriod($employee->lifecycle->separated_at);
@endphp

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Remaining Data Retention Period: ') }}
        <span class="fw-regular">{{ now()->diff($retentionPeriod)->format('%y years %m months %d days') }}</span>
    </x-slot:heading>

    <x-slot:description>
        <p><span class="fs-5 fw-bold mb-0">
            {{ "{$employee->status->emp_status_name} on: " }}
        </span>{{ $separationDate->copy()->format('F d, Y') }}</p>
    </x-slot:description>
</x-headings.main-heading>


<div class="col-md-12 d-flex align-items-center">
    @include('components.includes.tab_navs.archived-records-navs')
</div>

<!-- Information Tab Section-->

<section id="information" class="tab-section-employee">
    <livewire:hr-manager.employees.information :$employee />
</section>

<!-- Attendance Tab Section -->
<section id="attendance" class="tab-section-employee">
    <livewire:hr-manager.employees.attendance :$employee />
</section>

<!-- Payslips Tab Section -->
<section id="payslips" class="tab-section-employee">
    <livewire:hr-manager.employees.payslips :$routePrefix :$employee />
</section>

<!-- Contract Tab Section -->
<section id="contract" class="tab-section-employee">
    <livewire:hr-manager.employees.contract :$routePrefix :$employee />
</section>

<!-- Leaves Tab Section -->
<section id="leaves" class="tab-section-employee">
    <livewire:hr-manager.employees.leaves :$routePrefix :$employee />
</section>

<!-- Overtime Tab Section -->
<section id="overtime" class="tab-section-employee">
    <livewire:hr-manager.employees.overtime :$employee />
</section>

<!-- Payroll Summary Section -->
<section id="psummary" class="tab-section-employee">
    <livewire:hr-manager.employees.payroll-summary :$employee />
</section>

<!-- Trainings Section -->
<section id="trainings" class="tab-section-employee">
    <livewire:hr-manager.employees.trainings :$employee />
</section>

@endsection