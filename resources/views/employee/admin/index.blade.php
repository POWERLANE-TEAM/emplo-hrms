@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

@section('head')
<title>Home</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@pushOnce('scripts')
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

{{-- SECTION: Key Metrics --}}
<livewire:admin.dashboard.info-cards />

{{-- SECTION: Laravel Pulse & Recent Activity Logs --}}
<livewire:admin.dashboard.pulse-and-activity-logs />

{{-- SECTION: Announcements & Daily Time Record --}}
<section class="mb-5">
    <div class="d-flex mb-5 row">
        <livewire:admin.dashboard.latest-announcements />
        <livewire:admin.dashboard.daily-time-record />
    </div>
</section>

<section class="mb-5">
    <div class="d-flex mb-5 row">
        <div class="col-md-7 d-flex">
            <div class="card border-primary p-4 h-100 w-100">
                <div class="px-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="fs-3 fw-bold mb-3">Recent Activity Logs</div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex justify-content-end">
                                <x-buttons.view-link-btn link="#" text="View All" />
                            </div>
                        </div>
                    </div>
                    <div class="w-100">

                        <!-- BACK-END REPLACE: Recent Activity Logs. Limit to 2. -->
                        <ul>
                            <li class="fs-5 pb-2">You updated the <b>salary range</b> for Software Developer.</li>
                            <li class="fs-5 pb-2">You removed the <b>location</b> requirement from Customer Support
                                Representative.</li>
                            <li class="fs-5 pb-2">You added a <b>new open job position</b>: Graphic Designer.</li>
                            <li class="fs-5 pb-2">You changed the <b>job description</b> for Project Manager.</li>
                            <li class="fs-5 pb-2">You deleted a <b>qualification</b> from Marketing Specialist.</li>
                            <li class="fs-5 pb-2">You archived the <b>job posting</b> for Data Analyst.</li>
                            <li class="fs-5 pb-2">You added a <b>remote work option</b> for UX Designer.</li>
                            <li class="fs-5 pb-2">You updated the <b>required skills</b> for Content Writer.</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        <livewire:admin.dashboard.online-users />
    </div>
</section>
@endsection