@extends('components.layout.employee.layout', ['description' => 'Regulars Performance Evaluation', 'nonce' => $nonce])

@section('head')
<title>Regular Employees | Performance Evaluation</title>
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
        {{ __('Regulars Performance Evaluation Table') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and manage regular employees performance evaluation form.') }}
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.any-regulars-performances-table :$routePrefix />

@endsection
