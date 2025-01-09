@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>{{ $employee->last_name }} | Performance Evaluation</title>
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

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="'#'"> <!-- REPLACE: Link to the Performance Eval tables -->
            {{ __('Performance Evaluations') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.performances.create')">
            Assign Score
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>
{{-- @dd($employee) --}}
<section class="row">
    <div class="col-6">
        <x-headings.header-with-status title="{{ $employee->full_name }}" color="info" badge="{{ $employee->status->emp_status_name }}">
            <span class="fw-bold">Position: </span>
            {{ $employee->jobTitle->job_title }}
            </x-profile-header>
    </div>
    <div class="col-6 pt-2">
        <x-info_panels.callout type="info" :description="__('Learn more about the <a href=\'#\' class=\'text-link-blue\'>scoring evaluation</a> metrics and details.')">
        </x-info_panels.callout>

    </div>
</section>

<section class="mb-5 mt-3">
    <!-- Main Section -->
    <div class="d-flex mb-5 row align-items-stretch">

        <!-- Performance Category & Scoring -->
        <livewire:supervisor.evaluations.assign-score :employee="$employee" />

    </div>
</section>
@endsection