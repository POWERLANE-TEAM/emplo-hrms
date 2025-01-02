@extends('components.layout.employee.layout', ['description' => 'Create Incident Report', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Create Incident Report</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/incident.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])

@endPushOnce
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.relations.incidents.index')">
            Incidents
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.relations.incidents.create')">
            Create Report
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Create an Incident Report') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Please fill up the following fields.') }}
    </x-slot:description>
</x-headings.main-heading>

<section class="mb-5 mt-3">
    <livewire:hr-manager.incidents.create-incident-report />
</section>

@endsection