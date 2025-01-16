@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Archived 201 Records</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/archive.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/dashboard.css'])

@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Archived Employee 201 Records')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('View and track separated employees\' archived 201 records.') }}</p>
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.any-separated-employees-table :$routePrefix />

@endsection
