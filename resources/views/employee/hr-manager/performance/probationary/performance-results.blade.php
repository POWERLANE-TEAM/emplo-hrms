@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>EMP #'s Performance Evaluation Results</title>
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
        <x-breadcrumb :active="request()->routeIs($routePrefix . '.probationary-perf-results')">
            Probationary Employee
        </x-breadcrumb>
    </x-slot:breadcrumbs>
</x-breadcrumbs>


<!-- BACK-END REPLACE: Name,  Position-->
<x-headings.header-with-status title="Clark, Avery Mendiola" color="info" badge="Probationary">
    <span class="fw-bold">Position: </span>
    Associate / Assistant Manager
    </x-profile-header>


    <section class="mb-5 mt-3">
        <!-- Main Section -->
        <div class="d-flex mb-5 row align-items-stretch">
            <!-- Left Section: Overview -->
            <section class="col-md-5 d-flex">
                <div class="w-100">
                    <!-- Navigation Tabs -->
                    <div class="p-2">
                        @include('components.includes.tab_navs.eval-result-navs')
                    </div>

                    <!-- Overview: Navigation Tabs Content -->
                    <livewire:hr-manager.performance.overview-eval />
                </div>
            </section>

            <!-- Right Section: Performance Category -->
            <section class="col-md-7 d-flex">
                <div class="w-100">
                    <!-- Performance Category + Ratings -->
                    <livewire:hr-manager.performance.category-ratings />
                </div>
            </section>
        </div>

        <!-- Approve/Decline Buttons -->
        <div class="col-md-5 ps-3 pe-4">
            <div class="row">
                <!-- Decline -->
                <div class="col-6 pe-2">
                    <button type="submit" name="submit" class="btn btn-lg btn-danger col-6 w-100">Decline</button>
                </div>

               <!-- Approve -->
                <div class="col-6">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg col-6 w-100">Approve</button>
                </div>
            </div>
        </div>

    </section>
    @endsection

    <x-modals.data_dialogues.eval-approval-history />