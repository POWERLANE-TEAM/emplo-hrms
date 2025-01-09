@extends('components.layout.employee.layout', ['description' => 'Employee Performance Evaluation', 'nonce' => $nonce])

@section('head')
<title>{{ $performance->employeeEvaluatee->last_name }} | Performance Evaluation</title>
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
        <x-breadcrumb :href="route($routePrefix.'.performances.regulars.index')">
            {{ __('Performance Evaluations') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix.'.performances.regulars.show')">
            {{ $performance->employeeEvaluatee->full_name }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.header-with-status title="{{ $performance->employeeEvaluatee->full_name }}" color="info" badge="Regular">
    <span class="fw-bold">{{ __('Job Title: ') }}</span>
    {{ $performance->employeeEvaluatee->jobTitle->job_title }}
</x-profile-header>

<livewire:supervisor.evaluations.regulars.performance-approvals :$routePrefix :$performance />

@endsection
