@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
<title>Notifications</title>

<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/style.css'])

    <style>
        .notification-container {
            height: 100vh !important;
            width: 100vh !important;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@endPushOnce

@section('content')

<div class="row">
    <div class="col-md-12 d-flex justify-content-center">

        <h1>
            Notifications
        </h1>
    </div>
</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <div class="seperator mt-1 mb-3">
            <div class="wavy-line"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="notification-container">
            <livewire:notifications.notifs />
        </div>
    </div>
</div>


@endsection