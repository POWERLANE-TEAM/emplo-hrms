@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
    <title>{{  ucwords($applicationStatus) }} Applicants</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/applicants.js'])
    <!-- Adds the Core Table Styles -->
    @rappasoftTableStyles

    <!-- Adds any relevant Third-Party Styles (Used for DateRangeFilter (Flatpickr) and NumberRangeFilter) -->
    @rappasoftTableThirdPartyStyles

    <!-- Adds the Core Table Scripts -->
    @rappasoftTableScripts

    <!-- Adds any relevant Third-Party Scripts (e.g. Flatpickr) -->
    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/applicants.css'])
@endPushOnce
@section('content')
    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            Applicants
        </x-slot:heading>

        <x-slot:description>
            <p>Keep track of all new applications for available job positions.</p>
        </x-slot:description>
    </x-headings.main-heading>


    <livewire:employee.tables.hrmanager.applicants-table :applicationStatus="$applicationStatus"/>
@endsection
