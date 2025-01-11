@extends('components.layout.employee.layout', ['description' => 'Probationary Subordinates Performance Evaluations', 'nonce' => $nonce])

@section('head')
<title>Probationaries Performance Evaluations</title>
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
        {{ __('Probationaries Performance Evaluation') }}
    </x-slot:heading>

    <x-slot:description>
        {!! __('View and manage '.
        '<span class="text-primary fw-semibold">'
            .auth()->user()->account->jobTitle->jobFamily->job_family_name.
        '</span>'.
        '\'s probationaries performances here. Incomplete status will redirect you to the performance evaluation form.') !!}
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.probationary-subordinates-performances-table :$routePrefix />

@endsection