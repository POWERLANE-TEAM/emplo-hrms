{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Job Titles', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Job Titles</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/dashboard.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}


{{-- Body/Content Section --}}
@section('content')

<x-headings.header-link heading="{{ __('Organization') }}"
    description="{{ __('Manage the organization\'s job family and positions.') }}" label="Create" nonce="{{ $nonce }}"
    href="{{ route($routePrefix . '.job-title.create') }}" />

@include('components.includes.tab_navs.org-tab-navs')

<livewire:tables.job-title-table />

@endsection