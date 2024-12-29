@extends('components.layout.employee.layout', ['description' => 'My Reported Issues', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>My Reported Issues</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/issue.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])

@endPushOnce
@section('content')

<x-headings.header-link heading="{{ __('Report Issue') }}"
    description="{{ __('View and see the statuses of your submitted issues or complaints.') }}" label="Report Issue" nonce="{{ $nonce }}"
    href="{{ route($routePrefix . '.relations.issues.create') }}">
</x-headings.header-link>

<livewire:employee.tables.my-reported-issues-table />

@endsection
