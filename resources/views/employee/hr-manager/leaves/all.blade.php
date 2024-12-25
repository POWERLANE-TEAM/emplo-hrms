@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Employee Name's Performance Evaluation Results</title><!-- Replace with Employee Name -->
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])

@endPushOnce
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Requested Leaves Table') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and manage.') }}
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.any-leave-requests-table :$routePrefix />

@endsection