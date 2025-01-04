@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Leave Requests</title>
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

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Leave Requests') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and manage each employee\'s leave requests here.') }}
    </x-slot:description>
</x-headings.main-heading>

<div class="pb-2">
    @include('components.includes.tab_navs.leaves.hr-leaves-navs')
</div>

<livewire:employee.tables.any-leave-requests-table :$routePrefix />

@endsection