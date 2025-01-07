@extends('components.layout.employee.layout', ['description' => 'My Performance Evaluations', 'nonce' => $nonce])

@section('head')
<title>Performance Evaluations</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Probationaries Performance Evaluation Table') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and manage probationary employees performance evaluation form.') }}
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.performances')

<livewire:employee.tables.my-performances-as-regular-table :$routePrefix />

@endsection
