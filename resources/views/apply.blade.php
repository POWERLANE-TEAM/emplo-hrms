@extends('components.layout.app', ['description' => 'Guest Layout', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')


@section('head')
    <title>Apply</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
    @filepondScripts
    @vite(['resources/js/applicant/apply.js', 'resources/js/progress-bar.js'])
@endPushOnce

@section('critical-styles')
    @vite('resources/css/guest/secondary-bg.css')
@endsection

@pushOnce('pre-styles')
@endPushOnce

@pushOnce('styles')
    <link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet">
    @vite(['resources/css/applicant/apply.css'])
@endPushOnce

@section('before-nav')
    <x-layout.guest.secondary-bg />
@endsection

@section('header-nav')
    <x-layout.guest.secondary-header />
@endsection

@php
    $jobTitle = 'Software Engineer';
    // should be get from sewsion
@endphp

@section('content')
    <div class=" mt-4 mb-3 my-lg-5">
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'text-center fs-5'])" :overrideClass="true"
            class="text-primary fs-3 fw-bold mb-2">
            <x-slot:heading>
                {{ __('Applying for: ') . $jobTitle }}
            </x-slot:heading>

            <x-slot:description>
                {{ __('Please upload and fill in the necessary details.') }}
            </x-slot:description>
        </x-headings.main-heading>
    </div>

    <form id="application-wizard-form" {{-- wire:submit.prevent="save" --}} {{-- x-ref="resume-file" --}}>
        <livewire:form.applicant.application-wizard />
    </form>
@endsection

@section('footer')
    <x-guest.footer />
@endsection
