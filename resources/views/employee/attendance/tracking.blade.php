@extends('components.layout.employee.layout', ['description' => 'Payroll Summary', 'nonce' => $nonce])

@section('head')
<title>Attendance • Payroll Summary</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/attendance.css'])
@endPushOnce
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{ __('Attendance • Payroll Summary') }}
            </x-slot:heading>

            <x-slot:description>
                <p>{{ __('Manage each employee summary for a payroll period here.' ) }}</p>
            </x-slot:description>
        </x-headings.main-heading>
    </div>

    <livewire:employee.tables.any-payroll-summaries-table />
@endsection
