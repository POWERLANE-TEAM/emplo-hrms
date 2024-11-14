@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

@section('head')
<title>Home Page</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/admin/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/admin/dashboard.css'])
@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 pt-3 fw-bold mb-2">Good afternoon, {{ auth()->user()->account->first_name }}!</div>
    <p>It is <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>


<section role="navigation" aria-label="Quick Links" class="mb-5 row">
    <div class="col-md-3">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/active-accs.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Active Accounts</p>
                    <p class="fw-semibold fs-3">40</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-green" role="none">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/online-users.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Online Users</p>
                    <p class="fw-semibold fs-3">12</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-blue" role="none" aria-describedby="attendance-nav-desc">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/total-users.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Total Users</p>
                    <p class="fw-semibold fs-3">52</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-purple" role="none"
            aria-describedby="attendance-nav-desc">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/last-24-hours.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Logins Within 24h</p>
                    <p class="fw-semibold fs-3">40</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
        Key Metrics
    </header>


    <div class="d-flex mb-5 row">
        <!-- Laravel Pulse -->
        <div class="col-md-7">
            <x-nav-link href="{{ route('admin.system.pulse') }}" class="unstyled">
                <div class="card p-4 pulse-card">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="p-3">
                                <div class="fs-2 fw-bold text-primary card-cont-green-hover">Laravel Pulse</div>
                                <div class="fs-5 fw-regular card-cont-green-hover">Check the system’s performance and
                                    usage via Laravel Pulse.</div>
                            </div>
                        </div>
                        <div class="col-md-5 image-container">
                            <!-- Static Image -->
                            <img class="static-image"
                                src="{{ Vite::asset('resources/images/illus/dashboard/pulse-static.webp') }}" alt="">
                            <!-- Animated Image -->
                            <img class="animated-image"
                                src="{{ Vite::asset('resources/images/illus/dashboard/pulse-animated.gif') }}" alt="">
                        </div>
                    </div>
                </div>
            </x-nav-link>
        </div>



        <div class="col-md-5 border">
            Section 2
        </div>
    </div>

</section>

<livewire:admin.dashboard.online-users />

<section class="mb-5">
    <header class="fs-4 fw-bold text-primary mb-4" role="heading" aria-level="2">
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
        <span class="fs-4 fw-bold ps-1 pe-3">Employee Statistics</span>
    </header>

    <div class="d-flex">
        <div class="d-flex col-6 flex-md-wrap gap-3">
            <div class="card col-md-5 text-center p-3">
                <div class="fs-5 fw-bold">128</div>
                <small>Total Employment</small>
            </div>
            <div class="card col-md-5 text-center p-3">
                <div class="fs-5 fw-bold">128</div>
                <small>New Hires</small>
            </div>
            <div class="card col-md-5 text-center p-3">
                <div class="fs-5 fw-bold">128</div>
                <small>Departure</small>
            </div>
            <div class="card col-md-5 text-center p-3">
                <div class="fs-5 fw-bold">128</div>
                <small>On Probationary</small>
            </div>
        </div>
        <div class="col-6 card border-primary p-5">
            <div class="fs-3 fw-bold mb-4">Reminders</div>
            <div class="d-table w-100">
                <div class="d-table-row">
                    <div class="d-table-cell">Next Payslip Uploading</div>
                    <div class="d-table-cell">Due this Friday</div>
                </div>
                <div class="d-table-row">
                    <div class="d-table-cell">Performance Evaluation</div>
                    <div class="d-table-cell">Due this Friday</div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection