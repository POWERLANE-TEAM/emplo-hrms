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
<button class="btn btn-success mt-3" onclick="showToast('success', 'This is a success toast!', 'check-circle')">Show Success Alert</button>
<button class="btn btn-danger mt-3" onclick="showToast('danger', 'This is an error toast!!', 'alert-triangle')">Show Error Alert</button>
<button class="btn btn-warning mt-3" onclick="showToast('warning', 'This is a warning toast!!', 'alert-octagon')">Show Warning Alert</button>
<button class="btn btn-info mt-3" onclick="showToast('info', 'This is an info alert!', 'info')">Show Info Alert</button>

<div class="container mt-5">
    <h2 class="mb-4">Modals</h2>

    <button class="btn btn-success mt-3" onclick="openModal('success-modal')">Show Success Alert</button>
    <button class="btn btn-danger mt-3" onclick="openModal('error-modal')">Show Error Alert</button>


</div>


<x-modals.status-modal label="Success Notification" header="Success title here" id="success-modal" message="Action has been done successfully." type="success"/>
<x-modals.status-modal label="Error notification" header="Error title here" id="error-modal" message="Something went wrong. Please try again." type="error"/>

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