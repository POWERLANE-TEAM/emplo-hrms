@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>{{ ucfirst($tab) }} Employees Performance</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    {{--  --}}
@endPushOnce

@pushOnce('scripts')
    {{-- @vite(['resources/js/employee/.js']) --}}

    @rappasoftTableStyles

    @rappasoftTableThirdPartyStyles

    @rappasoftTableScripts

    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/performance.css'])
@endPushOnce

@section('content')
    {{-- <div class="d-flex justify-content-between align-items-center flex-wrap">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                Evaluation
            </x-slot:heading>

            <x-slot:description>
                <p>Organize performance evaluation forms.</p>
            </x-slot:description>
        </x-headings.main-heading>

        <div>
            <div class="d-flex flex-column column-gap-2 column-gap-lg-3">
                <button class="btn btn-primary btn-lg mb-2" disabled>
                    Issue Evaluation Form
                </button>
                <i>Performance evaluation period can be started on <b>
                        {{ \Carbon\Carbon::tomorrow()->format('F j, Y') }}</b>.</i>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
            [
                'title' => 'Probationary Employees',
                'route' => 'performance.evaluation.index',
                'routeParams' => ['employeeStatus' => 'probationary'],
            ],
            [
                'title' => 'Regular Employees',
                'route' => 'performance.evaluation.index',
                'routeParams' => ['employeeStatus' => 'regular'],
            ],
        ]" :isActiveClosure="function ($isActive, $item) use ($tab) {
            return $isActive && $tab === $item['routeParams']['employeeStatus'];
        }" />

        <button class="btn text-primary"><i class="icon icon-xlarge mx-2" style="transform: translateY(-5%)"
                data-lucide="star"></i> See Rankings</button>
    </div> --}}

    @if ($tab == 'probationary')
        @livewire('tables.performance.evaluation.probationary-table', ['routePrefix' => $routePrefix])
    @else
        {{ dd('regulr') }}
    @endif
    {{-- <livewire:tables.employees-attendance-table /> --}}
@endsection
