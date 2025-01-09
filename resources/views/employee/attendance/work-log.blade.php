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
<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.attendance.index' , ['range' => 'period'])">
            {{ __('Attendance Tracking') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.attendance.show')">
            {{ __('Employee Attendance') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

    <livewire:tables.attendance-breakdown-table :employee="$employee"/>
@endsection
