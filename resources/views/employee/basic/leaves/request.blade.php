@extends('components.layout.employee.layout', ['description' => 'Leave Request', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Request Leave</title>
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
        <x-breadcrumb :href="route($routePrefix . '.leaves.index')">
            Leaves
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.leaves.create')">
            Request Leave
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Request Leave') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Kindly fill up the following information.') }}
    </x-slot:description>
</x-headings.main-heading>


    <section class="mb-5 mt-3">
        <!-- Main Section -->
        <div class="d-flex mb-5 row align-items-stretch">
            <!-- Left Section: Holidays -->
            <section wire:ignore class="col-md-5 d-flex">
                <div class="w-100">
                    <livewire:calendar.holidays />
                </div>
            </section>

            <!-- Right Section: Request Leave Form -->
            <section class="col-md-7 d-flex">
                <div class="w-100">
                    <livewire:employee.leaves.request-leave />
                </div>
            </section>
        </div>

    </section>
    @endsection