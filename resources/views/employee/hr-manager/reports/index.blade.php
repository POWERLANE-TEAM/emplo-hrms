@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Reports</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/reports.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/reports.css'])

@endPushOnce
@section('content')

<section class="row">
    <div class="col-10">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{__('Annual Report')}}
            </x-slot:heading>

            <x-slot:description>
                <p>{{ __('View the annual key performance indicators.') }}</p>
            </x-slot:description>
        </x-headings.main-heading>
    </div>
    <div class="col-2 pt-2 text-end d-flex align-items-center justify-content-end w-25s">
        <x-form.boxed-dropdown id="select-year-report" :required="true" :nonce="$nonce"
            onchange="console.log('Selected Year:', this.value); window.dispatchEvent(new CustomEvent('year-changed', { detail: this.value }))"
            placeholder="Select year" />
    </div>
</section>

<div id="reports-container">
    <div class="reports-content hidden-until-load">
        <livewire:hr-manager.reports.employee-metrics />
        <livewire:hr-manager.reports.key-metrics />
        <livewire:hr-manager.reports.average-attendance-chart />
        <livewire:hr-manager.reports.absenteeism-report-chart />
        <livewire:hr-manager.reports.retention-turnover-chart />
        <livewire:hr-manager.reports.issue-resolution-chart />
        <livewire:hr-manager.reports.leave-utilization-chart />

        <div class="mt-4">
            <button class="btn btn-primary w-25"><i data-lucide="download" class="icon icon-large me-1"></i>
                {{ __('Download Report') }}</button>
        </div>

    </div>
    <div class="empty-state hidden-until-load">
        <div class="container">
            <!-- Row for the Image -->
            <div class="row justify-content-center mb-4 me-2">
                <div class="col-12 text-center">
                    <img class="img-size-30 img-responsive"
                        src="{{ Vite::asset('resources/images/illus/empty-states/reports.gif') }}" alt="">
                </div>
            </div>

            <!-- Row for the Text -->
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <p class="fs-3 fw-bold mb-0 pb-1">{{ date('Y') }} Annual Report Not Yet Available</p>
                    <p class="fs-6 fw-medium">The report will be automatically generated once the year ends.</p>
                </div>
            </div>

            <!-- Row for Buttons -->
            <div class="row justify-content-center">
                <!-- REPLACE STATIC PAGE LINK: Annual Reports Information -->
                <div class="col-12 text-center">
                    <a href="/information-centre?section=about-reports" id="toggle-information" class="text-link-blue text-decoration-underline fs-7 hover-opacity">
                        Learn more about the Annual Report generation.
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection