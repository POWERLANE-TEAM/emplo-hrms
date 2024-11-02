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
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
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

<x-headings.header-link heading="Create New Department" description="Create a new department of the company."
    label="Bulk Creation" nonce="{{ $nonce }}" href="{{ route($guard . '.accounts') }}" />

    <x-sub-navs.tabular-nav :guard="$guard" :items="[
    ['title' => 'Department', 'route' => 'create-dept', 'active' => true],
    ['title' => 'Position', 'route' => 'create-position', 'active' => false],
]" />


{{--
|--------------------------------------------------------------------------
| Announcement Form
|--------------------------------------------------------------------------
--}}

<section class="mx-2">
    <form>
        {{-- Input field for: Department Title --}}
        <x-form.boxed-input-text id="dep_name" label="Department Name" name="dep_name"
            :nonce="$nonce" :required="true">
        </x-form.boxed-input-text>

        {{-- Textarea field for: Description --}}
        <x-form.boxed-textarea id="dep_desc" label="Department Description" name="dep_desc" :nonce="$nonce"
            :rows="6" :required="true" />

        {{-- Submit Button --}}
        <x-buttons.main-btn id="create_dep" label="Create Department" name="create_dep" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
    </form>
</section>
@endsection



{{--
|--------------------------------------------------------------------------
| List of IDs and Names
| *Both ID and Name attributes share the same naming convention.
|--------------------------------------------------------------------------
| Element | ID/Name | Description
|--------------------------------------------------------------------------
| Department Name | dep_name | Title of the announcement
| Description        | dep_desc  | Main description field
| Post Announcement | create_dep | Button to post announcement
|--------------------------------------------------------------------------
--}}