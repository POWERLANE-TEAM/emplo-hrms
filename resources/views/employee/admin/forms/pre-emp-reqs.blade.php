{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Department</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('pre-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}



{{-- Body/Content Section --}}
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        Configure Forms
    </x-slot:heading>

    <x-slot:description>
        Set up and configure all of the forms within the system.
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.forms-tab-navs')

<p class="py-2">Pre-employment requirements are the list of documents or attachments that pre-employed applicants needs
    to submit before proceeding.</p>

{{--
* |--------------------------------------------------------------------------
* | Pre-Employment Requirements Section
* |--------------------------------------------------------------------------
--}}


{{-- Placeholder datas. Need to be mounted properly from the db. --}}
@php
    $customOptions = ['SSS Registration Record (E-1/E-4/ID/Contribution/Emp. History)', 'CEDULA/ Community Tax Certificate', 'Barangay Clearance', 'Police Clearance/NBI Clearance']; // Replace this with data fetched from db
@endphp


{{-- Grid Table of Pre-Emp Requirements --}}
@livewire('blocks.dragdrop.show-draggable-data', ['items' => $customOptions])


{{-- Add Another Pre-Emp Field --}}
@livewire('blocks.inputs.add-drag-item', [
    'label' => 'Add Pre-Employment Requirement',
    'required' => true,
    'id' => 'pre-emp-input',
    'name' => 'pre-emp-input',
])

@endsection

{{-- Edit Dialogue --}}
<x-modals.edit-draggable-data></x-modals.edit-draggable-data>