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

<x-headings.header-link heading="{{ __('Bulk Upload Payslips') }}"
    description="{{ __('Upload payslips to securely store and manage employee payment records.') }}"
    label="Individual Upload" nonce="{{ $nonce }}" href="#">
</x-headings.header-link>

<section class="mt-3">

    <x-info_panels.callout type="info" :description="__('Learn more about the <a href=\'#\' class=\'text-link-blue\'>scoring evaluation</a> metrics and details.')">
    </x-info_panels.callout>

    <div class="px-3 pt-4">
        <p class="fs-3 fw-bold">Payslips for: July 1 - July 15, 2024</p><!--BACK-END REPLACE: Change with the current payroll period. -->
    </div>

    <!-- Dropzone -->
    <div class="mt-1 px-3 py-4 w-100">
        <div class="filepond-wrapper custom-filepond-style">
            <input type="file" class="filepond" name="file" accept="application/pdf, image/*" required multiple>
        </div>
    </div>

    <!-- Note -->
    <div class="pt-2 px-3">
    <x-info_panels.note
    :note="'This is a bulk file upload of all employees\' payslips. Please upload a zip file containing all employee payslips, ensuring each file name includes the employees\' full name. If unsure about the format, please refer to the uploading <a href=\'#\' class=\'text-link-blue\'>guidelines</a>.'" />

    </div>

    <!-- Submit Button -->
    <div class="pt-4 d-flex align-items-center text-end">
        <x-buttons.main-btn label="Upload Payslips" wire:click.prevent="save" :nonce="$nonce" :disabled="false" class="w-25" :loading="'Submitting...'" />
    </div>
</section>
@endsection