@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
<title>HR Dashboard</title>
<link rel="preload" href="{{ Vite::asset('resources/css/employee/admin/dashboard.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ Vite::asset('resources/css/employee/admin/dashboard.css') }}"></noscript>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/dashboard.css'])
    @vite(['resources/css/employee/admin/dashboard.css'])

    <style>
.indiv-grid-container-1 {
    max-height: 50vh;
}
    </style>
@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 fw-bold mb-2">Good afternoon, {{ Auth::user()->account->first_name }}!</div>
    <p>It is <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>
<section role="navigation" aria-label="Quick Links" class="mb-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-body-secondary border-0 px-md-5 py-4" role="none"
                aria-describedby="applicants-nav-desc">
                <x-nav-link href="/employee/leaves/requests/general" class="unstyled">
                    <div class="mb-3">
                        <span></span>
                        <div class="fs-4 fw-bold">Applicants</div>
                    </div>
                    <div class="card-text" id="applicants-nav-desc">
                        Review and verify candidates, resume and documents.
                    </div>
                </x-nav-link>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary text-white border-0 px-md-5 py-4" role="none"
                aria-describedby="leaves-nav-desc">
                <x-nav-link href="/employee/leaves/requests/general" class="unstyled">
                    <div class="mb-3">
                        <span></span>
                        <div class="fs-4 fw-bold text-white">Leaves</div>
                    </div>
                    <div class="card-text text-white" id="leaves-nav-desc">
                        Track pending leave requests, leave types, and approvals.
                    </div>
                </x-nav-link>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-body-secondary border-0 px-md-5 py-4" role="none"
                aria-describedby="attendance-nav-desc">
                <x-nav-link href="/employee/attendance/daily" class="unstyled">
                    <div class="mb-3">
                        <span></span>
                        <div class="fs-4 fw-bold">Attendance</div>
                    </div>
                    <div class="card-text" id="attendance-nav-desc">
                        Monitor employees' attendance, absences, and workday hours.
                    </div>
                </x-nav-link>
            </div>
        </div>
    </div>
</section>


<livewire:admin.dashboard.info-cards />

<x-section-wrapper>
    
    <livewire:admin.dashboard.latest-announcements />
    <livewire:admin.dashboard.recent-activity-logs />
</x-section-wrapper>

@endsection