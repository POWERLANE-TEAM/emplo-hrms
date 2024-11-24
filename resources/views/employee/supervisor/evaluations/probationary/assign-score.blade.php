@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Employee Name's Performance Evaluation Results</title><!-- Replace with Employee Name -->
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])

@endPushOnce
@section('content')

<x-breadcrumbs>
    <x-slot:breadcrumbs>
        <x-breadcrumb :href="'#'"> <!-- REPLACE: Link to the Performance Eval tables -->
            Evaluations
        </x-breadcrumb>
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.assign-score.probationary')">
            Assign Score
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>


<!-- BACK-END REPLACE: Name,  Position-->
<section class="row">
    <div class="col-6">
        <x-headings.header-with-status title="Clark, Avery Mendiola" color="info" badge="Probationary">
            <span class="fw-bold">Position: </span>
            Associate / Assistant Manager
            </x-profile-header>
    </div>
    <div class="col-6 pt-2">
        <x-info_panels.callout type="info" :description="__('Learn more about the <a href=\'#\' class=\'text-link-blue\'>scoring evaluation</a> metrics and details.')">
        </x-info_panels.callout>

    </div>
</section>

<section class="mb-5 mt-3">
    <!-- Main Section -->
    <div class="d-flex mb-5 row align-items-stretch">

        <!-- Performance Category & Scoring -->
        <section class="col-md-12 d-flex">
            <div class="w-100">
                <livewire:supervisor.evaluations.assign-score />
            </div>
        </section>

        <!-- Final Rating & Scale -->
        <!-- Back-end Note: This will only show once all scores (up to finals) are assigned. -->
        <section class="col-md-12 d-flex">
            <div class="w-100">
                <livewire:supervisor.evaluations.final-grading />
            </div>
        </section>

        <!-- Comments -->
        <section class="col-md-12 d-flex">
            <div class="w-100">
                <livewire:supervisor.evaluations.comments />
            </div>
        </section>

        <!-- Button -->
        <section class="col-md-12">
            <div class="row">
                <!-- Note -->
                <div class="col-5 ps-3">
                    <x-info_panels.note
                        note="{{ __('This form requires your signature. By clicking submit, your signature will be automatically added to the downloadable file.') }}" />
                </div>
                <!-- Button -->
                <div class="col-7 text-end align-items-center justify-content-center">
                    <x-buttons.main-btn label="Submit Evaluation" wire:click.prevent="save" :nonce="$nonce"
                        :disabled="false" class="w-50" :loading="'Submitting...'" />
                </div>
            </div>
        </section>
    </div>
</section>
@endsection