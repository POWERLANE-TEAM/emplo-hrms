@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>Employees Attendance</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    {{--  --}}
@endPushOnce

@pushOnce('scripts')
    {{-- @vite(['resources/js/employee/.js']) --}}

    @rappasoftTableStyles

    @rappasoftTableThirdPartyStyles

    @rappasoftTableScripts

    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/attendance.css'])
@endPushOnce
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                Attendance Tracking
            </x-slot:heading>

            <x-slot:description>
                <p>Monitors the hours worked, absences and leaves.</p>
            </x-slot:description>
        </x-headings.main-heading>

    </div>


    <livewire:tables.employees-attendance-period-table />
    {{-- <livewire:tables.attendance-breakdown-table :employee="3"/> --}}
@endsection
