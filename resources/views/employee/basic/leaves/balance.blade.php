@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Leaves</title>
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

<x-headings.header-link heading="{{ __('Leave Balance') }}"
    description="{{ __('Manage your leave balance and request leaves.') }}" label="Request Leave" nonce="{{ $nonce }}"
    href="{{ route($routePrefix . '.leaves.create') }}">
</x-headings.header-link>


<div class="pb-2">
    @include('components.includes.tab_navs.leaves.general-leaves-navs')
</div>

<!-- REPLACE STATIC PAGE LINK: Leave Policy -->

<x-info_panels.callout type="info" :description="__('Leave balances reset annually on January 1st. Learn more about the company\'s <a href=\'#\' class=\'text-link-blue hover-opacity\'>leave policy</a>')"></x-info_panels.callout>

<!-- BACK-END REPLACE TABLE: Replace with Leave Balance table -->

@endsection