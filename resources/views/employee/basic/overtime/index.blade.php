@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Overtime Requests</title>
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
            {{ __('Overtime Requests') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.overtimes.index')">
            {{ __('Archive Records') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<livewire:employee.overtimes.basic.cut-off-payout-periods />

@include('components.includes.tab_navs.leaves-navs')

<section class="my-2">
    <livewire:employee.tables.basic.overtimes-table />
</section>

@endsection
