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

<div class="row pt-3">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="notification-container">

            <div class="row px-4 mb-3">
                <div class="col-md-10">
                    <h4 class="mb-0 fw-bold">Notifications</h4>
                </div>
                <div class="col-md-2 text-end mb-3">
                    Mark all as read
                </div>
            </div>

            <livewire:notifications.notifs />
        </div>
    </div>
</div>


@endsection