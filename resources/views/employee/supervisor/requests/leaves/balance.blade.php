@extends('components.layout.employee.layout', ['description' => 'Leave Requests', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Leaves</title>
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
        {{ __('Leaves Table') }}
    </x-slot:heading>

    <x-slot:description>
        {!! __('View and keep track of ').
            '<span class="text-primary fw-semibold">' 
                .auth()->user()->account->jobTitle->jobFamily->job_family_name. 
            '</span>'.
            __(' employees\' leave balance.') !!}
    </x-slot:description>
</x-headings.main-heading>

<div class="pb-2">
    @include('components.includes.tab_navs.leaves.supervisor-leaves-navs')
</div>

<x-info_panels.callout type="info" :description="__('Leave balances reset annually on January 1st. Learn more about the company\'s <a href=\'/information-centre?section=leave-policy\' class=\'text-link-blue hover-opacity\' target=\'blank\' >leave policy</a>')"></x-info_panels.callout>

<livewire:employee.tables.subordinate-sil-credits-table />

@endsection