@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce, 'main_cont_class' => 'mb-0'])

@section('head')
    <title>Home Page</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($routePrefix . '.applicants')">
                Applicants
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($routePrefix . '.applicant.*')">
                Applicant Profile
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>

    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            Applicant Profile
        </x-slot:heading>

        <x-slot:description>
            <p>Review the applicant's information and resume below.</p>
        </x-slot:description>
    </x-headings.main-heading>

    @livewire('employee.applicants.show', ['application' => $application])
@endsection
