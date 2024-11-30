@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Resume Evaluator</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
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
        <p>{{ __('Tool that analyzes skills and qualifications in resumes to identify the most suitable and best-fit candidates for the open job positions.') }}</p>
    </x-slot:description>
</x-headings.main-heading>


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
                    <livewire:hr-manager.evaluations.overview-eval />
                </div>
            </section>

            <!-- Right Section: Performance Category -->
            <section class="col-md-7 d-flex">
                <div class="w-100">
                    <!-- Performance Category + Ratings -->
                    <livewire:hr-manager.evaluations.category-ratings />
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