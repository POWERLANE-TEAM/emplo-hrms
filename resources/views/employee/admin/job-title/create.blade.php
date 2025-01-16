{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Create Job Title', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Job Title</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js', 'resources/js/modals.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}


{{-- Body/Content Section --}}
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.job-titles.index')">
            Job Title List
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.job-titles.create')">
            Create Job Title
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Create Job Title') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Kindly fill-in the fields below.') }}
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.job-tab-navs')

<x-info_panels.callout type="info"
    description="{{ __('Ensure the job family is added before assigning a position, otherwise it will not appear.') }}" note="true">
</x-info_panels.callout>

<section class="mx-2">

    <livewire:admin.job-title.create-job-title-form />

</section>
@endsection

{{-- Edit Dialogue / About Qualification Modal --}}

<x-modals.informational.about-qualification />