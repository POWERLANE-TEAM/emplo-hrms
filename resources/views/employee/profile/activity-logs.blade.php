@extends('components.layout.employee.layout', ['nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Settings & Privacy</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    {{-- --}}
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')
<x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'ps-2 pt-2 pb-2 ms-n1'])" :overrideContainerClass="true" class="fs-2 fw-bold mb-2 text-secondary-emphasis">
    <x-slot:heading>
        {{ __('Settings & Privacy') }}
    </x-slot:heading>
</x-headings.main-heading>

@include('components.includes.tab_navs.settings-privacy-navs')

<div>
    <livewire:profile.activity-logs />
</div>
@endsection