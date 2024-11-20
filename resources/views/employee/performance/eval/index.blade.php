@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>Employees Performance{{ ' | ' . $subpage }}</title>
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
    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            Evaluation
        </x-slot:heading>

        <x-slot:description>
            <p>Organize performance evaluation forms.</p>
        </x-slot:description>
    </x-headings.main-heading>


    {{-- <livewire:tables.employees-attendance-table /> --}}
@endsection
