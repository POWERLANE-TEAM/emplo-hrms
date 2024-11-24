@extends('components.layout.applicant.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
<title>Applicant</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
    @vite(['node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js'])
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/applicant/dashboard.js'])
@endPushOnce

@pushOnce('pre-styles')
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/pre-employment.css'])
@endPushOnce

@section('content')
<div class="container">

    <!-- Header Greetings -->
    <hgroup>
        <div class="fs-1 fw-bold">Hello, Jermiah!</div>
        <h1><span id="applicant-id-label" class="fs-5 fw-normal">Applicant ID: </span> <strong
                aria-labelledby="applicant-id-label" class="fs-5 text-primary">PWRLN-212-SR</strong></h1>
    </hgroup>

    <!-- BACK-END REPLACE:
     Replace with the applicant's status to
     show/hide sections for each stage. -->

    @php
        $isScreening = false;
        $isScheduled = false;
        $isPreEmployed = true;
    @endphp

    <!-- Info Cards -->
    <section class="mb-5">
        <div class="row mt-md-5 gap-5 flex-md-nowrap mb-5">
            <div class="col-md-6 card border-0 bg-body-secondary text-center p-5">
                <label for="applicant-status" class="text-uppercase text-primary fw-medium">Current Status</label>
                <strong id="applicant-status" class="applicant-status fs-3 fw-bold">
                    Assessment Scheduled
                </strong>
            </div>
            <div class="col-md-6 card border-0 bg-body-secondary text-center p-5 ">
                <label for="applicant-position" class="text-uppercase text-primary fw-medium">Applied Job
                    Position</label>
                <strong id="applicant-position" class="applicant-position fs-3 fw-bold">
                    Assistant HR Manager
                </strong>
            </div>

        </div>
        @if($isScheduled || $isScreening)
        <div class="px-4">
            <div class="callout callout-info bg-body-tertiary"> <i class="icon p-1 mx-2 text-info"
                    data-lucide="message-circle-warning"></i>Status
                updates will be provided periodically. Please ensure to check back
                regularly for
                the latest information.
            </div>
        </div>
        @endif
    </section>

    <!-- Show only when Scheduled -->
    @if($isScheduled)
        <livewire:applicant.stages.scheduled />
    @endif

    <!-- Show only when Pre-Employed -->
    @if($isPreEmployed)
        <livewire:applicant.stages.pre-employed />
    @endif

</div>
@endsection