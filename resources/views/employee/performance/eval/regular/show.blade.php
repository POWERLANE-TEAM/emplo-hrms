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
        <x-breadcrumb :href="route($routePrefix.'.performances.regular')">
            {{ __('Performance Evaluations') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix.'.performances.regular.performance')">
            {{ __('Your Performance') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<div class="row">
    <div class="col">
        <x-info_panels.callout 
            type="info" 
            :description="__('Acknowledging this evaluation form will serve as your signature and approval.')">
        </x-info_panels.callout>
    </div>    
</div>

<livewire:employee.performances.regular.performance-approvals :$routePrefix :$performance />

@endsection
