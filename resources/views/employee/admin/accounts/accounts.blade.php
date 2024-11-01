{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
    <title>Home Page</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
    
{{-- TABLES FOR ACCOUNTS--}}

@endsection
