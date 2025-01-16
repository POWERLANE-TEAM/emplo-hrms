@extends('components.layout.employee.layout', ['description' => 'Employee Trainings', 'nonce' => $nonce])

@section('head')
<title>My Training Records</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/issue.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])
@endPushOnce

@section('content')
<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('My Training Records') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and manage your training records here.') }}
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.my-trainings-table :$routePrefix />

@endsection