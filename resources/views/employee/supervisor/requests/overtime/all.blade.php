@extends('components.layout.employee.layout', ['description' => 'Overtime Requests', 'nonce' => $nonce])

@section('head')
<title>Overtime Requests</title>
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
        {{ __('Overtime Requests') }}
    </x-slot:heading>

    <x-slot:description>
        {!! __('Manage overtime requests of ').
            '<span class="text-primary fw-semibold">' 
                .auth()->user()->account->jobTitle->jobFamily->job_family_name. 
            '</span>'.
            __(' employees here.') !!}
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.overtime-requests-table />
<livewire:employee.overtimes.request-overtime-approval />

@endsection