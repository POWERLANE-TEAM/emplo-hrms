@use('App\Enums\ApplicationStatus')
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
            <div class="fs-1 fw-bold">Hello, {{ $application->applicant->first_name }}!</div>
            <h1><span id="applicant-id-label" class="fs-5 fw-normal">Applicant ID: </span> <strong
                    aria-labelledby="applicant-id-label"
                    class="fs-5 text-primary">PWRLN-{{ $application->application_id }}</strong></h1>
        </hgroup>

        <!-- BACK-END REPLACE:
                                                                                                                     Replace with the applicant's status to
                                                                                                                     show/hide sections for each stage. -->

        {{-- {{ dd($application->application_status_id == ApplicationStatus::PENDING->value) }} --}}
        @php
            $isPending = $application->application_status_id == ApplicationStatus::PENDING->value;
            $isNotHired = $application->hired_at === null;
            $isNotPassed = $application->is_passed === false;
            $hasNoInitialInterview = $application->initialInterview === null;
            $hasNoFinalInterview = $application->finalInterview === null;

            if ($isPending) {
                $isScreening = true;
                // pending application should not have any values for the assessment
                if ($isPending && !($isNotPassed || $isNotHired || $hasNoInitialInterview || $hasNoFinalInterview)) {
                    report('Application status is pending but has assigned values for the assessment.', [
                        'application_id' => $application->application_id,
                        'applicant_id' => $application->applicant_id,
                        'applicant_name' => $application->applicant->fullname,
                        'is_passed' => $application->is_passed,
                        'hired_at' => $application->hired_at,
                        'initialInterview' => $application->initialInterview,
                        'finalInterview' => $application->finalInterview,
                    ]);
                    $isScreening = false;
                }
            }

            $isScheduled = $application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED->value;

            $isPreEmployed = $application->application_status_id == ApplicationStatus::PRE_EMPLOYED->value;

            $missingAssessment = $isNotPassed || $isNotHired || $hasNoInitialInterview || $hasNoFinalInterview;

            if ($isPreEmployed && $missingAssessment) {
                report('Application status is preemployed but but not assessed yet.', [
                    'application_id' => $application->application_id,
                    'applicant_id' => $application->applicant_id,
                    'applicant_name' => $application->applicant->fullname,
                    'is_passed' => $application->is_passed,
                    'hired_at' => $application->hired_at,
                    'initialInterview' => $application->initialInterview,
                    'finalInterview' => $application->finalInterview,
                ]);
            }
        @endphp

        <!-- Info Cards -->
        <section class="mb-5">
            <div class="row mt-md-5 column-gap-5 flex-md-nowrap mb-5">
                <div class="flex-1-sm-grow card border-0 bg-body-secondary text-center p-5">
                    <label for="applicant-status" class="text-uppercase text-primary fw-medium">Current Status</label>
                    <strong id="applicant-status" class="applicant-status fs-3 fw-bold text-capitalize ">
                        {!! when($isPending && !$isScreening, '<span class="text-danger"> ! </span>') !!}
                        {{ $application->status->application_status_name }}
                    </strong>
                </div>
                <div class="flex-1-sm-grow card border-0 bg-body-secondary text-center p-5 ">
                    <label for="applicant-position" class="text-uppercase text-primary fw-medium">Applied Job
                        Position</label>
                    <strong id="applicant-position" class="applicant-position fs-3 fw-bold">
                        {{ $application->vacancy->jobTitle->job_title }}
                    </strong>
                </div>

            </div>
            @if ($isScheduled || (isset($isScreening) && $isScreening))
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
        @if ($isScheduled)
            <livewire:applicant.stages.scheduled :application="$application" />
        @endif

        <!-- Show only when Pre-Employed -->
        @if ($isPreEmployed /* && !$missingAssessment */)
            {{-- <livewire:applicant.stages.pre-employed :application="$application" :isMissingAssement="$missingAssessment" /> --}}
        @endif

    </div>
@endsection
