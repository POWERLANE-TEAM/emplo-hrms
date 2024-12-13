@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Archive Overtimes</title>
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
        <x-breadcrumb :href="route($routePrefix . '.overtimes')">
            {{ __('Overtime Summaries') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.overtimes.archive')">
            {{ __('Archive Records') }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>    
</x-breadcrumbs>

<livewire:employee.overtimes.basic.cut-off-payout-periods />

@include('components.includes.tab_navs.leaves-navs')

<section class="my-2">
    <livewire:employee.tables.basic.archive-overtimes-table />
</section>

@endsection
