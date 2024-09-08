@php
    $nonce = csp_nonce();
@endphp

@extends('layout.applicant', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
    <title>Applicant</title>
    <script src="build/assets/nbp.min.js" defer></script>
    @vite(['resources/js/applicant/dashboard.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['vendor/node_modules/jquery/dist/jquery.slim.min.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
    @vite(['vendor/node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js'])
@endsection


@section('content')
    <hgroup>
        <div class="fs-1 fw-bold">Hello, Jermiah!</div>

        <h2 class="fs-5 fw-normal">Applicant ID: <strong class="fs-5 text-primary">PWRLN-212-SR</strong></h2>
    </hgroup>

    <section class="mb-5">

        <div class="row mt-md-5 gap-5 flex-md-nowrap mb-5">
            <div class="col-md-6 card border-0 bg-body-secondary text-center p-5">
                <label for="applicant-status" class="text-uppercase text-primary">Current Status</label>
                <strong id="applicant-status" class="applicant-status fs-3 fw-bold">
                    Assessment Scheduled
                </strong>
            </div>
            <div class="col-md-6 card border-0 bg-body-secondary text-center p-5 ">
                <label for="applicant-position" class="text-uppercase text-primary">Applied Job Position</label>
                <strong id="applicant-position" class="applicant-position fs-3 fw-bold">
                    Assistant HR Manager
                </strong>

            </div>

        </div>
        <div class="px-4">
            <div class="callout callout-success bg-body-tertiary"> <i class="icon p-1 mx-2 text-info"
                    data-lucide="message-circle-warning"></i>Status
                updates will be provided periodically. Please ensure to check back
                regularly for
                the latest information.
            </div>
        </div>

    </section>

    <section class="mb-5">
        <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
            <span></span>
            Schedule of Assessment
        </header>

        <div class="row flex-md-nowrap gap-5">
            <section class="d-flex flex-column col-md-6 gap-3">
                <div class="col-md-12 card border-0 bg-body-secondary text-center p-5 gap-3">
                    <label for="applicant-exam-date" class="text-uppercase text-primary">Examination</label>
                    <strong id="applicant-exam-date" class="applicant-exam-date fs-5 fw-bold">
                        Assistant HR Manager
                    </strong>

                </div>
                <div class="col-md-12 card border-0 bg-body-secondary text-center p-5 gap-3">
                    <label for="applicant-interview-date" class="text-uppercase text-primary">Initial Interview</label>
                    <strong id="applicant-interview-date" class="applicant-interview-date fs-5 fw-bold">
                        Assistant HR Manager
                    </strong>

                </div>
            </section>
            <div class="bg-primary text-white card border-0 col-md-6 p-5 gap-3">
                <header class="fs-4 fw-bold">
                    <span></span>
                    Notice
                </header>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque eius illum ipsa corporis similique
                    impedit
                    natus porro, aspernatur asperiores in excepturi voluptatibus rem distinctio eos eveniet laudantium
                    temporibus suscipit tempora.
                </p>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
            <span class="fs-4 fw-bold ps-1 pe-3">Pre-Employment Requirements</span>
            @livewire('components.status-badge', ['color' => 'danger', 'content' => 'Incomplete'])
        </header>

        <div class="row flex-md-nowrap gap-5">
            <div class="col-md-6 p-3">
                <div class="position-relative mx-auto">
                    <canvas id="chartProgress" class=""></canvas>
                </div>

            </div>
            <section class="d-flex flex-column col-md-6 px-5 gap-4">
                <header class="fw-semibold fs-5 ">
                    Status Metric
                </header>
                <div class=" d-flex flex-column gap-3">
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-info  d-inline" data-lucide="badge-info"></i>
                        </span>
                        <span>Pending for review: </span>
                        <b>4</b>
                    </div>
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-success  d-inline" data-lucide="badge-check"></i>
                        </span>
                        <span>Verified documents: </span>
                        <b>4</b>
                    </div>
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-danger  d-inline" data-lucide="badge-alert"></i>
                        </span>
                        <span>Awaiting Resubmission: </span>
                        <b>4</b>
                    </div>

                </div>
                <small>
                    <i><b>Note: </b>Status updates will be provided periodically. Review of pending documents may take 1-3
                        days.</i>
                </small>
            </section>
        </div>
    </section>

    <nav class="w-100 d-flex mb-5">
        <a href="#" class="btn btn-primary btn-lg mx-auto px-5 text-capitalize"> <span><i
                    class="icon p-1 mx-2 d-inline" data-lucide="plus-circle"></i></span>Go to submission
            page</a>
    </nav>
@endsection
