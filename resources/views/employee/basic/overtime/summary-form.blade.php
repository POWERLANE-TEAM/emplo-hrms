@extends('components.layout.employee.layout', ['description' => 'Overtime Summary Form', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Overtime Summary Form</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@props([
    'filter' => $filter,
])

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/leaves.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/leaves.css'])

@endPushOnce
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix . '.overtimes.index')">
            {{ __('Overtime Cut-Offs') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.overtimes.summaries')">
            {{ __('Summary Record') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<livewire:employee.overtimes.basic.cut-off-payout-periods
    :payroll="$filter"
/>
<livewire:employee.overtimes.overtime-summary-approval 
    :payroll="$filter"
/>

@include('components.includes.tab_navs.leaves-navs')

<section class="my-2">
    <livewire:employee.tables.cut-off-overtime-summary-approvals-table />
</section>
@endsection
