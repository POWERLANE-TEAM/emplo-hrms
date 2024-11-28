@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Requesed Overtime</title>
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
        <x-breadcrumb :href="'#'"> <!-- REPLACE: Link to the All Overtime Summary Form tables -->
            Overtime Summaries
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.overtime.summary-form')">
            Current Overtime Form
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>


<section class="row">
    <div class="col-6">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{__('Overtime Summary Form')}}
            </x-slot:heading>

            <x-slot:description>
                <p><b>{{ __('Payroll Period:') }}</b> {{ __('September 01, 2024 - September 28, 2024') }}</p>
                <!-- BACK-END REPLACE: Replace with current payroll period. -->
            </x-slot:description>
        </x-headings.main-heading>
    </div>
    <div class="col-6 pt-2 text-end">
        <button onclick="openModal('requestOvertime')" class="btn btn-primary">
        <i data-lucide="plus-circle" class="icon icon-large me-2"></i> Request Overtime</button>
    </div>
</section>

@include('components.includes.tab_navs.leaves-navs')

<section class="my-2">
    <!-- BACK-END REPLACE: Table of all requested overtime. Approved, Pending, etc. -->
</section>

<x-modals.create_dialogues.request-overtime />
@endsection
