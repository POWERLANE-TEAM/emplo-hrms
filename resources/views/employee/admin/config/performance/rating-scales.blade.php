{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Performance Scales</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
    @vite(['resources/js/drag-and-drop.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}

{{-- Body/Content Section --}}
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Configure Performance Evaluation') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Add, edit or remove performance evaluations elements.') }}
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.perf-eval-navs')

<livewire:admin.config.performance.rating-scales />

@endsection