@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
<title>Home Page</title>

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
@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 pt-3 fw-bold">{{ ('Good afternoon, ') . auth()->user()->account->first_name }}!</div>
    <p>{{ __('It is') }} <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>

{{-- Key Info Cards --}}
<livewire:employee.dashboard.info-cards />


@endsection