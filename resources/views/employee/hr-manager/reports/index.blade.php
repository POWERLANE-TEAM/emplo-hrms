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
        <div class="d-flex justify-content-center align-items-center text-center w-100 h-100 py-5">
            <div class="mt-4">
                <img class="img-size-40 img-responsive text-wrap fadein-text"
                    src="{{ Vite::asset('resources/images/illus/help-centre/search-content.gif') }}" alt="">

                <div class="typewriter-text fs-2 fw-bold" aria-label="We are hiring!">
                    Welcome to Information Centre!
                </div>

                <div class="w-50 mx-auto">
                    <p class="text-wrap fadein-text">A helpful space where you can find important system policies and
                        key information. It’s your one-stop
                        place to stay informed about the guidelines and details you need.</p>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection