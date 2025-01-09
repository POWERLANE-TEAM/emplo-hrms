@extends('components.layout.employee.layout', ['description' => 'Employee Performance Evaluation', 'nonce' => $nonce])

@section('head')
<title>{{ $performance->employeeEvaluatee->last_name }} | Performance Evaluation</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])
@endPushOnce

@section('content')

<livewire:employee.performances.regular.performance-approvals :$routePrefix :$performance />

@endsection
