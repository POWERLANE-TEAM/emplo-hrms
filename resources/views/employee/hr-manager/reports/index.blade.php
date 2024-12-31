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
                {{__('Reports')}}
            </x-slot:heading>

            <x-slot:description>
                <p>{{ __('View the annual key performance indicators.') }}</p>
            </x-slot:description>
        </x-headings.main-heading>
    </div>
    <div class="col-2 pt-2 text-end d-flex align-items-center justify-content-end w-25s">
        <x-form.boxed-dropdown id="priority" :required="true" :nonce="$nonce"
            :options="['2024' => '2024', '2023' => '2023', '2022' => '2022']"
            placeholder="Select year" />
    </div>
</section>

<livewire:hr-manager.reports.kpi/>

<livewire:hr-manager.reports.leave-utilization-chart />

@endsection