@extends('components.layout.employee.layout', ['description' => 'Employees Payslips', 'nonce' => $nonce])

@section('head')
<title>Employees Payslips</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xslt.js/0.5.1/xslt.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/payslip.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/payslip.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Payslips Table') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('View and manage each employee\'s payslips here.') }}
    </x-slot:description>
</x-headings.main-heading>

<livewire:employee.tables.any-employee-payslips-table :$routePrefix />

<livewire:employee.payslip.upload-payslip />

@endsection