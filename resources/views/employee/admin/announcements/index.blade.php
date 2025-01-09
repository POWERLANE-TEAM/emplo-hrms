{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Home Page</title>
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

<x-headings.header-link heading="{{ __('Announcements') }}"
    description="{{ __('Create, edit and delete announcements.') }}" label="Add Announcement" nonce="{{ $nonce }}"
    href="{{ route($routePrefix . '.announcement.create') }}" />

<!-- BACK-END TABLE REPLACE: List of Announcements -->

@endsection