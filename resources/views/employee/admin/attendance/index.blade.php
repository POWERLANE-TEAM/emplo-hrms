@extends('components.layout.employee.layout', ['nonce' => $nonce])

@section('head')
    <title>Attendance Logs</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/admin/dashboard.js'])

    {{-- Adds the Core Table Styles --}}
    @rappasoftTableStyles
    {{-- Adds any relevant Third-Party Styles (Used for DateRangeFilter (Flatpickr) and NumberRangeFilter) --}}
    @rappasoftTableThirdPartyStyles
    {{-- Adds the Core Table Scripts --}}
    @rappasoftTableScripts
    {{-- Adds any relevant Third-Party Scripts (e.g. Flatpickr) --}}
    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

    {{-- <livewire:admin.manage-attendance-logs /> --}}
    <livewire:admin.attendance-logs-table />

@endsection