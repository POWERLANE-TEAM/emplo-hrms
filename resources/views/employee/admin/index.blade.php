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
        <div class="col-7">
            {{-- I don't what should go here --}}
        </div>
    <livewire:admin.dashboard.online-users />            
    </div>    
</section>
@endsection