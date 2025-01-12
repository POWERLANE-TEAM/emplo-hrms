@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Separation</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>

<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script> <!-- Remove once the back-end package is being used -->
<script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>


@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/separation.js'])
@endPushOnce

@pushOnce('pre-styles')
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet"> <!-- Remove once the back-end package is being used -->
    <link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet">
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/separation.css'])
    <style>

        .filepond--list {
            height: 100vh;
        }
    </style>
@endPushOnce
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Submit Resignation Letter')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('Upload your resignation letter here.') }}</p>
    </x-slot:description>
</x-headings.main-heading>

<section class="mx-4">
    <div class="mt-1 pt-1 pb-3 py-4 w-100">
        <div class="filepond-wrapper custom-filepond-style">
            <input multiple type="file" class="filepond" name="file" accept="application/pdf">
            <!-- BACK-END REPLACE: Replace thiS input with the x-filepond::upload component. Use search -->

        </div>
    </div>

    <!-- REPLACE STATIC PAGE LINK: Separation Process Policy & Company Policies -->
    <div class="pt-2 px-3">
    <x-info_panels.note :note="'Ensure your resignation letter is complete before submission. Review the
    <a href=\'#\' class=\'text-link-blue hover-opacity\'>separation process</a> and
    <a href=\'#\' class=\'text-link-blue hover-opacity\'>company policies</a>
    to understand the legal implications of separation. Once submitted, this will initiate the resignation process.'" />
    </div>

    <div class="pt-4  d-flex align-items-center text-end">
        <x-buttons.main-btn label="File Resignation Letter" wire:click.prevent="save" :nonce="$nonce" :disabled="false"
            class="w-25" :loading="'Submitting...'" />
    </div>
</section>

@endsection