{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Category</title>
<link rel="preload" href="{{ Vite::asset('resources/css/employee/main.css') }}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/employee/main.css') }}">
</noscript>

<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/admin/dashboard.js'])
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

<livewire:admin.config.performance.period-setup.start />
<livewire:admin.config.performance.period-setup.timeframe />

@endsection