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
    @vite(['resources/js/admin/dashboard.js'])
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



<div class="row">

    <!-- Calendar -->
    <div class="col-md-9">
        <div id="calendar"></div>
    </div>

    <!-- Information / Manage -->
    <div class="col-md-3">

        <div class="ms-5">
            <livewire:calendar.events-legends />
            <livewire:calendar.add-event />
        </div>
    </div>
</div>

<x-modals.create_dialogues.add-event />

@endsection