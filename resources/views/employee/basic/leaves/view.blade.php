@extends('components.layout.employee.layout', ['description' => 'Leave Request', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'leave' => $leave,
    'status' => $status,
])

@section('head')
<title>View Requested Leave</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/leaves.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/leaves.css'])

@endPushOnce
@section('content')

@php
    $activeRoutes = [];
    $ownRequest = $routePrefix.'.leaves.show';
    $otherRequest = $routePrefix.'.leaves.requests.general';

    array_push($activeRoutes, $ownRequest, $otherRequest);
@endphp

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix.'.leaves.index')">
            {{ __('Leaves') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($activeRoutes)">
            {{ __('Request Leave') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        <span class="me-2">
            {{ $leave->category->leave_category_name }}
        </span>
        <x-status-badge 
            color="{{ $status->getColor() }}"
        >
            {{ $status->getLabel() }}
        </x-status-badge>
    </x-slot:heading>

    <x-slot:description>
        @if (request()->routeIs($otherRequest))
            <div class="text-secondary-emphasis">
                <span class="me-5">
                    <span class="fw-semibold">
                        {{ __('Employee: ') }}
                    </span>
                    {{ $leave->employee->full_name }}
                </span>
                <span>
                    <span class="fw-semibold">
                        {{ __('Filed On: ') }}
                    </span>
                    {{ $leave->filed_at }}
                </span>
            </div>
        @endif
    </x-slot:description>
</x-headings.main-heading>

    <section class="mb-5 mt-3">
        <div class="d-flex mb-5 row align-items-stretch">
            <section class="col-md-5 d-flex">
                <div class="w-100">
                    <livewire:employee.leaves.approvals :$leave />
                </div>
            </section>

            <section class="col-md-7 d-flex">
                <div class="w-100">
                    <livewire:employee.leaves.leave-info :$leave />
                </div>
            </section>
        </div>

    </section>
@endsection