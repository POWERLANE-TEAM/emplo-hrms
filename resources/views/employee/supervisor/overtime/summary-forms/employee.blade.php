@extends('components.layout.employee.layout', ['description' => 'Employee Overtime Summary', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'employee'  => $employee,
    'filter'    => $filter,
])

@section('head')
<title>{{ $employee->last_name }} | Overtime Summaries</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
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
        <x-breadcrumb :href="route($routePrefix . '.overtimes.index')">
            {{ __('Overtime Summaries') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.overtimes.requests.employee.summaries')">
            {{ __("{$employee->last_name} Summary Form") }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

{{-- 
    $employee returns model 
    $filter returns (int) id
--}}
<livewire:employee.overtimes.cut-off-payout-periods-approval
    :employee="$employee"
    :payroll="$filter"
/>

<section class="my-2">
    <livewire:employee.tables.employee-overtime-request-summaries-table :employee="$employee" />
</section>

@endsection