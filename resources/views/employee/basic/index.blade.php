@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
    <title>Home Page</title>

    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/dashboard.css'])
@endPushOnce

@section('content')
    <div class="fs-2 fw-bold mb-5 ms-n1">Good afternoon, {{ Auth::user()->account->first_name }}!</div>

    <section class=" mb-5 d-flex gap-5">
        <div class="card bg-body-secondary col-md-4 border-0 p-md-5">
            <div>
                <span></span>
                <h2 class="fs-4 fw-bold">Hours Worked</h2>
            </div>

            <div>
                <b>Last Month:</b> 160 hours
                <b>This Month:</b> 20 hours (so far)
            </div>
        </div>
        <div class="card bg-primary text-white col-md-4  border-0 p-md-5">
            <div>
                <span></span>
                <h2 class="fs-4 fw-bold text-white">Leave Balance</h2>
            </div>

            <div>
                <b>Last Month:</b> 160 hours
                <b>This Month:</b> 20 hours (so far)
            </div>
        </div>
        <div class="card bg-body-secondary col-md-4  border-0 p-md-5">
            <div>
                <span></span>
                <h2 class="fs-4 fw-bold">Next Payslips</h2>
            </div>

            <div>
                <b>Last Month:</b> 160 hours
                <b>This Month:</b> 20 hours (so far)
            </div>
        </div>

    </section>

    <section class="mb-5">
        <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
            <span>
                <picture>
                    <source media="(min-width:2560px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-xxl.webp') }}">
                    <source media="(min-width:768px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-md.webp') }}">
                    <source media="(min-width:576px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-sm.webp') }}">
                    <source media="(max-width:320px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-xs.webp') }}">

                    <img width="28" height="28" aspect-ratio="1/1" class="icon" loading="lazy"
                        src="{{ Vite::asset('resources/images/icons/green-calendar-md.webp') }}" alt="">
                </picture>
            </span>
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
                    <span>
                        <picture>
                            <source media="(min-width:2560px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/white-push-pin-xxl.webp') }}">
                            <source media="(min-width:1200px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/white-push-pin-xl.webp') }}">
                            <source media="(min-width:992px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/white-push-pin-lg.webp') }}">
                            <source media="(min-width:768px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/white-push-pin-md.webp') }}">
                            <source media="(min-width:576px)" class=""
                                srcset="{{ Vite::asset('resources/images/icons/white-push-pin-sm.webp') }}">

                            <img width="28" height="28" aspect-ratio="1/1" class="" loading="lazy"
                                src="{{ Vite::asset('resources/images/icons/white-push-pin-md.webp') }}" alt="">
                        </picture>
                    </span>
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
            <x-status-badge color="danger">Incomplete</x-status-badge>
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
                    <i><b>Note: </b>Status updates will be provided periodically. Review of pending documents may take
                        1-3
                        days.</i>
                </small>
            </section>
        </div>
    </section>
@endsection
