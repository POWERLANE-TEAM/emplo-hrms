{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Passing Rate Range</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('pre-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}



{{-- Body/Content Section --}}
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        Configure Performance Evaluation
    </x-slot:heading>

    <x-slot:description>
        Add, edit or remove performance categories.
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.perf-eval-navs')

<section>

    <p>Passing rate range shows if an employee passed the evaluation or not.</p>


    {{-- Placeholder datas. Need to be mounted properly from the db. --}}
    @php
        $placeholderItems = [
            ['data-one' => '2.0', 'data-two' => '5.0'],
            ['data-one' => '1.0', 'data-two' => '1.9'],
        ]; // Replace this with data fetched from db
    @endphp


    {{-- Customization of the data rendering in the table --}}
    @php
        $dataOneHtml = array_map(function ($item, $index) {
            // Determine the status and corresponding class based on the index
            if ($index === 0) {
                $status = 'PASSED';
                $statusClass = 'text-success fw-bold';
            } else {
                $status = 'FAILED';
                $statusClass = 'text-danger fw-bold';
            }
            return "<span class='{$statusClass}'>{$status}: </span><span>{$item['data-one']} - </span>";
        }, $placeholderItems, array_keys($placeholderItems));

        $dataTwoHtml = array_map(function ($item) {
            return "<span>{$item['data-two']}</span>";
        }, $placeholderItems);
    @endphp


    {{-- Draggable Grid Table --}}
    @livewire('blocks.dragdrop.show-mult-drag-data', [
    'items' => $placeholderItems,
    'dataOneHtml' => $dataOneHtml,
    'dataTwoHtml' => $dataTwoHtml,
    'editCallback' => 'openEditPerfRangeModal'
])

</section>


@endsection

{{-- Edit Category Dialogue --}}
<x-modals.edits_dialogues.edit-perf-range />