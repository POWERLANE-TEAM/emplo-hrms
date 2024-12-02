@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

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

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="'#'"> <!-- REPLACE: Link to the Employee's Leaves table -->
            Leaves
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.leaves.view')">
            Request Leave
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Vacation Leave Request') }}
    </x-slot:heading>

    <x-slot:description>
        {!! __('Current Status: <span class="text-blue fw-bold">Awaiting Approval</span>') !!}
        <!-- BACK-END Replace: Current status of the Requested Leave. Change the color as well. Make it text-success when approved. -->
    </x-slot:description>
</x-headings.main-heading>


    <section class="mb-5 mt-3">
        <!-- Main Section -->
        <div class="d-flex mb-5 row align-items-stretch">
            <!-- Left Section: Approvals -->
            <section class="col-md-5 d-flex">
                <div class="w-100">
                    <livewire:employee.leaves.approvals />
                </div>
            </section>

            <!-- Right Section: Requested Leave Details -->
            <section class="col-md-7 d-flex">
                <div class="w-100">
                    <livewire:employee.leaves.leave-info />
                </div>
            </section>
        </div>

    </section>
    @endsection