@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Separation</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/separation.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/separation.css'])

@endPushOnce
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.separation.coe')">
            Requests
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.separation.coe.request')">
            Issue Certificate
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<livewire:hr-manager.separation.coe.issue-coe-requests />

@endsection