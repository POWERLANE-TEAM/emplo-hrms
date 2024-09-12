@php
    $nonce = csp_nonce();
@endphp

@extends('layout.employee', ['description' => 'Employee Dashboard', 'nonce' => $nonce, 'user' => $user])

@section('head')
    <title>Home Page</title>

    {{-- Critical Assets that will cause cumulative shift if late loaded --}}
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script nonce="{{ $nonce }}">
        lucide.createIcons();
    </script>

    @vite(['resources/js/employee/hr/dashboard.js'])
@endsection

@section('content')
    <div class="fs-2 fw-bold mb-5 ms-n1">Good afternoon, {{ $user->account->first_name }}!</div>

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

            Key Metrics
        </header>


    </section>

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

    </section>
@endsection
