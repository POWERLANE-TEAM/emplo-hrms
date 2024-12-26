@extends('components.layout.employee.layout', ['nonce' => $nonce])

@section('head')
<title>Calendar Manager</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr/dashboard.js'])
    @vite(['resources/js/calendar.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Calendar Manager')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('Organizes company schedules and dates, and ensure efficient planning and coordination across departments.') }}
        </p>
    </x-slot:description>
</x-headings.main-heading>


<div class="container">
    <div class="row">

        <!-- Calendar -->
        <div class="col-md-7">
            <div id="calendar"></div>
        </div>

        <!-- Information / Manage -->
        <div class="col-md-5">

            <div class="ms-5">
                <div class="mb-2">
                    <h5 class="mt-2 mb-0 letter-spacing-2 text-uppercase text-primary fw-semibold">For September</h5>
                </div>

                <div>
                    <h2 class="fw-bold pb-3">Events Legends</h2>

                    <section>
                        <div class="d-flex align-items-center mb-2">
                            <span class="bi bi-circle-fill text-info fs-5 me-2"></span>
                            <span class="fs-5 fw-medium"><span class="fw-bold text-info">4</span> Regular Holidays</span>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <span class="bi bi-circle-fill text-warning fs-5 me-2"></span>
                            <span class="fs-5 fw-medium"><span class="fw-bold text-warning">3</span> Special Non-working</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="bi bi-circle-fill text-primary fs-5 me-2"></span>
                            <span class="fs-5 fw-medium"><span class="fw-bold text-primary">2</span> Company Events</span>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection