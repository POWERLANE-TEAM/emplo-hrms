@extends('components.layout.centre.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
    <title>Applicant</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
    @vite(['node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js'])
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/applicant/dashboard.js'])
@endPushOnce

@pushOnce('pre-styles')
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/centre.css'])
@endPushOnce

@section('content')
    <div class="container"></div>
@endsection