@extends('components.layout.employee.layout', ['description' => 'Report Issue', 'nonce' => $nonce])

@section('head')
<title>Report Issue</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/issue.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])
@endPushOnce

@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.relations.issues.index')">
            {{ __('Issues') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.relations.issues.create')">
            {{ __('Report Issue') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Report an Issue') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('File formal grievances or concerns regarding workplace issues, policy violations, or interpersonal conflicts.') }}
    </x-slot:description>
</x-headings.main-heading>

<section class="mb-5 mt-3">

    <livewire:employee.issues.create-issue />

</section>

<x-modals.informational.about-conf-preference />

@endsection
