@extends('components.layout.employee.layout', ['description' => 'Employee Issue Report', 'nonce' => $nonce])

@section('head')
<title>{{ $issue->reporter->last_name }} | Issue Report</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@props([
    'issue' => $issue,
])

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/issue.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])

@endPushOnce
@section('content')

<livewire:hr-manager.issues.issue-info :$issue :$routePrefix />

<x-modals.informational.about-conf-preference />

@endsection