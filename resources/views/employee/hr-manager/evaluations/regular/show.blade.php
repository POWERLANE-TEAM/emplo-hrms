@extends('components.layout.employee.layout', ['description' => 'Regular Performance Evaluation', 'nonce' => $nonce])

@section('head')
<title>{{ $performance->employeeevaluatee->last_name }} | Performance Evaluation</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])
@endPushOnce

@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix.'.performances.probationaries.general')">
            {{ __('Performance Evaluations') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix.'.performances.probationaries.review')">
            {{ $performance->employeeevaluatee->full_name }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.header-with-status title="{{ $performance->employeeevaluatee->full_name }}" color="info" badge="Regular">
    <span class="fw-bold">{{ __('Job Title: ') }}</span>
    {{ $performance->employeeevaluatee->jobTitle->job_title }}
</x-profile-header>

<livewire:hr-manager.evaluations.regulars.performance-approvals :$routePrefix :$performance />

@endsection