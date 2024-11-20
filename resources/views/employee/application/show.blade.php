@php
    $nonce = csp_nonce();
@endphp

@php
    $applicantName = $application->applicant->fullname;
@endphp

@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
    <title>Applicant Profile | {{ $applicantName }}</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    {{--  --}}
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/applicants.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
    @vite(['resources/css/employee/applicants.css'])
@endPushOnce

@section('content')
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($routePrefix . '.applications')">
                Applicants
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($routePrefix . '.application.*')">
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

    @php
        $modalId = 'application-approve-modal';
    @endphp

    @livewire('employee.applicants.show', ['application' => $application, 'modalId' => $modalId])
    @livewire('employee.modal.applicant.resume.approve', ['application' => $application, 'modalId' => $modalId])
@endsection
