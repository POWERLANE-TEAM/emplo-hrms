{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Performance Scales</title>
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

    <p>The assigned numerical ratings used to rate an employee’s performance in a category.</p>
    

    {{-- Placeholder datas. Need to be mounted properly from the db. --}}
    @php
        $placeholderItems = [
            ['data-one' => '1', 'data-two' => 'Needs Improvement'],
            ['data-one' => '2', 'data-two' => 'Meets Expectations'],
            ['data-one' => '3', 'data-two' => 'Exceeds Expectations'],
            ['data-one' => '4', 'data-two' => 'Outstanding']
        ]; // Replace this with data fetched from db
    @endphp


    {{-- Customization of the data rendering in the table --}}
    @php
        $dataOneHtml = array_map(function($item) {
            return "<span class='fw-bold text-primary'>{$item['data-one']} =</span>";
        }, $placeholderItems);

        $dataTwoHtml = array_map(function($item) {
            return "<span class='text-muted'>{$item['data-two']}</span>";
        }, $placeholderItems);
    @endphp


    {{-- Draggable Grid Table --}}
    @livewire('blocks.dragdrop.show-mult-drag-data', [
    'items' => $placeholderItems,
    'dataOneHtml' => $dataOneHtml,
    'dataTwoHtml' => $dataTwoHtml,
    'editCallback' => 'openEditPerfScalesModal'])

    
    {{-- Add Category Button --}}
    <x-buttons.dotted-btn-open-modal label="Add Performance Scale" modal="addPerfScale" :disabled="false" />
</section>


@endsection

{{-- Add / Edit Category Dialogue --}}
<x-modals.edits_dialogues.edit-perf-scale />
<x-modals.create_dialogues.add-perf-scale />