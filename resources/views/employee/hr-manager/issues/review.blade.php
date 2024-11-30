@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Submit Complaint</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/issue.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])

@endPushOnce
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="'#'"> <!-- REPLACE: Link to the All Issues table -->
            Issues
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.issues.review')">
            Submitted Complaint
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<div class="row">
    <div class="col-9">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{ __('Review Complaint') }}
            </x-slot:heading>

            <x-slot:description>
                {!! __('<b>Submitted By:</b> Cristian Manalang') !!}
                <!-- BACK-END REPLACE: Replace with name. If selected confidentiality is = Anonymous, name should be labeled Anonymous. -->
            </x-slot:description>
        </x-headings.main-heading>
    </div>

    <div class="col-3 justify-content-end d-flex align-items-center">
        <div>
            <small class="fst-italic text-end">Submitted on: January 08, 2024</small>
            <!-- BACK-END REPLACE: Submission date of the Issue. -->
        </div>
    </div>

</div>
<section class="mb-5 mt-3">
    <!-- Submit Issue Form -->
    <livewire:hr-manager.issues.issue-info />

</section>

<x-modals.informational.about-conf-preference />
<x-modals.create_dialogues.add-issue-resolution />

@endsection