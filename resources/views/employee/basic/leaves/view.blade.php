@extends('components.layout.employee.layout', ['description' => 'Leave Request', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@props([
    'leave' => $leave,
])

@section('head')
<title>View Requested Leave</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/leaves.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/leaves.css'])

@endPushOnce
@section('content')

<livewire:employee.leaves.requestor-info :$routePrefix :$leave />

<section class="mb-5 mt-3">
    <div class="d-flex mb-5 row align-items-stretch">
        <section class="col-md-5 d-flex">
            <div class="w-100">
                <livewire:employee.leaves.approvals :$leave />
            </div>
        </section>

        <section class="col-md-7 d-flex">
            <div class="w-100">
                <livewire:employee.leaves.leave-info :$routePrefix :$leave />
            </div>
        </section>
    </div>

</section>

@endsection