@extends('components.layout.employee.layout', ['description' => 'Create Incident Report', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>{{ $incident->created_at->format('j M Y g:i A') }} | Incident Report</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@props([
    'incident' => $incident
])

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/incident.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])
@endPushOnce

@section('content')

<livewire:hr-manager.incidents.incident-report-info :$routePrefix :$incident />

@endsection