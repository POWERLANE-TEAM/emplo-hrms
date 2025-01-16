@extends('components.layout.employee.layout', ['description' => 'Employee Home', 'nonce' => $nonce])

@section('head')
<title>Home Page</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/style.css'])

    <style>
.indiv-grid-container-2 {
    max-height: 30vh;
}
    </style>

@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 pt-3 fw-bold">{{ ('Good afternoon, ') . auth()->user()->account->first_name }}!</div>
    <p>{{ __('It is') }} <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>

{{-- Key Info Cards --}}
<livewire:employee.dashboard.info-cards />

<!-- DTR & Announcements -->
<section>
    <div class="row px-3">
        <livewire:employee.dashboard.daily-time-record />
        <livewire:employee.dashboard.latest-announcements />
    </div>
</section>

@endsection