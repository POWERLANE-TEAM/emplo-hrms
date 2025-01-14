@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Leave Balance</title>
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
        {{ __('Leave Balance') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and keep track of employees\'s leave balance.') }}
    </x-slot:description>
</x-headings.main-heading>

<div class="pb-2">
    @include('components.includes.tab_navs.leaves.hr-leaves-navs')
</div>

<!-- REPLACE STATIC PAGE LINK: Leave Policy -->

<x-info_panels.callout type="info" :description="__('Leave balances reset annually on January 1st. Learn more about the company\'s <a href=\'#\' class=\'text-link-blue hover-opacity\'>leave policy</a>')"></x-info_panels.callout>

<livewire:employee.tables.any-employee-sil-credits-table />

@endsection