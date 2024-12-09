@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

@section('head')
<title>Home</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/admin/dashboard.css'])
@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 pt-3 fw-bold">{{ ('Good afternoon, ').auth()->user()->account->first_name }}!</div>
    <p>{{ __('It is') }} <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>

{{-- Key Metrics --}}
<livewire:admin.dashboard.info-cards />

{{-- Laravel Pulse & Calendar Events --}}
<x-section-wrapper :header="'Key Metrics & Logs'">
    <livewire:admin.dashboard.laravel-pulse />
    <livewire:admin.dashboard.calendar-events />
</x-section-wrapper>

{{-- Latest Announcements & DTR --}}
<x-section-wrapper>
    <livewire:admin.dashboard.daily-time-record /> 
    <livewire:admin.dashboard.latest-announcements />   
</x-section-wrapper>

{{-- Recent Activity Logs & Online Users --}}
<x-section-wrapper>
    <livewire:admin.dashboard.recent-activity-logs />
    <livewire:admin.dashboard.active-sessions />
</x-section-wrapper>
@endsection