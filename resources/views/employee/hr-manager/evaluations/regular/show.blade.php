@extends('components.layout.employee.layout', ['description' => 'Regular Performance Evaluation', 'nonce' => $nonce])

@section('head')
<title>{{ $performance->employeeevaluatee->last_name }} | Performance Evaluation</title>
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

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="route($routePrefix.'.performances.regulars.general')">
            {{ __('Performance Evaluations') }}
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix.'.performances.regulars.review')">
            {{ $performance->employeeevaluatee->full_name }}
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>

<x-headings.header-with-status title="{{ $performance->employeeevaluatee->full_name }}" color="info" badge="Regular">
    <div class="d-flex position-relative">
        <div>
            <span class="fw-bold">{{ __('Job Title: ') }}</span>
            {{ $performance->employeeevaluatee->jobTitle->job_title }}
        </div>

        <form  action="{{route($routePrefix . '.performances.plan.improvement.regular.generate')}}" method="post" class="d-contents">
            @csrf
            <input type="hidden" name="performance-form" value="{{$performance->regular_performance_id}}">
            <input type="hidden" name="employee-status" value="{{$performance->employeeevaluatee->status->emp_status_id}}">

            <button type="submit" class="btn btn-primary rounded-circle py-2 px-1 ms-auto translate-middle-y" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Click to generate a performance improvement plan for {{$performance->employeeevaluatee->first_name}}.">
                <span class="m-2"><i class="icon icon-xxl "
                    data-lucide="sparkle"></i></span>
                </button>
        </form>
    </div>
</x-profile-header>

<livewire:hr-manager.evaluations.regulars.performance-approvals :$routePrefix :$performance />

@endsection
