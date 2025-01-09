@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Payslips Bulk Upload</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xslt.js/0.5.1/xslt.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>

<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/payslip.js'])

@endPushOnce

@pushOnce('pre-styles')
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
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
        {{ __('View and manage payslips.') }}
    </x-slot:description>
</x-headings.main-heading>

@endsection