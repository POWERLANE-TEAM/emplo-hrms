@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>Separation</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>

@endsection

@pushOnce('pre-scripts')

@endPushOnce

@pushOnce('scripts')
<script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
@filepondScripts
    @vite(['resources/js/employee/basic/separation.js'])
@endPushOnce

@pushOnce('pre-styles')

    <!-- Remove once the back-end package is being used -->
    <link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet">
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/separation.css'])
    <style>
        /* works but is limited to the height of container */
        /* .filepond--root:has(.filepond--browser:not(:invalid)) .filepond--list{
            height: 100vh;
        } */
    </style>
@endPushOnce
@section('content')
    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            {{ __('Submit Resignation Letter') }}
        </x-slot:heading>

        <x-slot:description>
            <p>{{ __('Upload your resignation letter here.') }}</p>
        </x-slot:description>
    </x-headings.main-heading>

    <livewire:employee.separation.resignation-request>
@endsection
