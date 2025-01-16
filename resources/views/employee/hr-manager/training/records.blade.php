@extends('components.layout.employee.layout', ['description' => 'Employee Training Record', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>{{ "{$employee->last_name} • Training Records" }}</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/issue.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/incident.css'])
@endPushOnce

@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix.'.trainings.general')"> 
            {{ __('Training Records') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix.'.trainings.general.employee')">
            {{ __("{$employee->last_name}'s Records") }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<livewire:hr-manager.training.employee-records :$routePrefix :$employee />

@endsection