@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Balance</title>
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

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Leave Balance') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and keep track of your leave balance.') }}
    </x-slot:description>
</x-headings.main-heading>

<div class="pb-2">
    @include('components.includes.tab_navs.leaves.general-leaves-navs')
</div>

<x-info_panels.callout type="info" :description="__('Leave balances reset annually on January 1st. Learn more about the company\'s <a href=\'/information-centre?section=leave-policy\' class=\'text-link-blue hover-opacity\' target=\'blank\' >leave policy</a>')"></x-info_panels.callout>

@endsection