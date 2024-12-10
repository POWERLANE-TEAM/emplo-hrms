@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Overtime | Recent Records</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>

{{-- Adds the Core Table Styles --}}
@rappasoftTableStyles
{{-- Adds any relevant Third-Party Styles (Used for DateRangeFilter (Flatpickr) and NumberRangeFilter) --}}
@rappasoftTableThirdPartyStyles
{{-- Adds the Core Table Scripts --}}
@rappasoftTableScripts
{{-- Adds any relevant Third-Party Scripts (e.g. Flatpickr) --}}
@rappasoftTableThirdPartyScripts
@endsection

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
            {{ __('Overtime Summaries') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.overtimes.recents')">
            {{ __('Recent Records') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>


<section class="row">
    <div class="col-6">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{__('Overtime Requests')}}
            </x-slot:heading>

            <x-slot:description>
                <p><b>{{ __('Payroll Period:') }}</b> {{ __('September 01, 2024 - September 28, 2024') }}</p>
                <!-- BACK-END REPLACE: Replace with current payroll period. -->
            </x-slot:description>
        </x-headings.main-heading>
    </div>
    <div class="col-6 pt-2 text-end">
        <button onclick="openModal('requestOvertimeModal')" class="btn btn-primary">
        <i data-lucide="plus-circle" class="icon icon-large me-2"></i> Request Overtime</button>

        <!-- BACK-END REPLACE NOTE: This button should not appear if the OT Summary Form being viewed is history/not the current payroll period. -->
    </div>
</section>

@include('components.includes.tab_navs.leaves-navs')

<section class="my-2">
    <livewire:employee.tables.basic.recent-overtimes-table />
    <livewire:employee.overtimes.basic.request-overtime />
</section>
@endsection
