@extends('components.layout.employee.layout', ['description' => 'Incident Table', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Reported Incidents</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/incident.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])

@endPushOnce
@section('content')

<x-headings.header-link heading="{{ __('Incidents') }}"
    description="{{ __('Document and manage the incidents.') }}"
    label="Create Report" nonce="{{ $nonce }}" href="{{ route($routePrefix . '.relations.incidents.create') }}">
</x-headings.header-link>

<livewire:employee.tables.reported-incidents-table :$routePrefix />

@endsection