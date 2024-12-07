@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Resume Evaluator</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/evaluator.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/evaluator.css'])

@endPushOnce
@section('content')
<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Resume Evaluator')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('Tool that analyzes skills and qualifications in resumes to identify the most suitable and best-fit candidates for the open job positions.') }}
        </p>
        {{-- <x-info_panels.callout type="info" :description="__('Learn more about the <a href=\'#\' class=\'text-link-blue\'>weighted computation</a> using keyword-based analysis of resume content.')">
        </x-info_panels.callout> --}}
    </x-slot:description>
</x-headings.main-heading>

<livewire:hr-manager.resume-evaluator.show-rankings :routePrefix="$routePrefix" />
@endsection