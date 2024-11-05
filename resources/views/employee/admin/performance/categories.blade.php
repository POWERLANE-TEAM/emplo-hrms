{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Category</title>
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
        Add, edit or remove performance evaluations elements.
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.perf-eval-navs')

<section>

    <p class="">Performance categories encompass the key areas of evaluation used to assess an employee's work,
        contributions and overall performance.</p>
    

    {{-- Placeholder datas. Need to be mounted properly from the db. --}}
    @php
        $placeholderItems = [
            ['data-one' => 'Quantity of Work', 'data-two' => 'Consistently delivers high-quality work, meeting deadlines and completing tasks efficiently with minimal  supervision.'],
            ['data-one' => 'Quality of Work', 'data-two' => 'Produces well-executed, thorough, and accurate work, ensuring effectiveness.'],
            ['data-one' => 'Capacity to Develop', 'data-two' => 'Readily accepts new and challenging assignments, demonstrating a willingness to learn and grow  professionally.'],
            ['data-one' => 'Leadership', 'data-two' => 'Always keeps and holds himself accountable for achieving goals and objectives.']
        ]; // Replace this with data fetched from db
    @endphp


    {{-- Customization of the data rendering in the table --}}
    @php
        // Prepare HTML arrays for dataOne and dataTwo
        $dataOneHtml = array_map(function ($item) {
            return "<div class='fw-bold text-primary fs-5'>{$item['data-one']}</div>";
        }, $placeholderItems);

        $dataTwoHtml = array_map(function ($item) {
            return "<div>{$item['data-two']}</div>";
        }, $placeholderItems);

        // One Liner
        //$dataOneHtml = array_map(function($item) {
        //    return "<span class='fw-bold'>{$item['data-one']}</span>";
        //}, $placeholderItems);

        //$dataTwoHtml = array_map(function($item) {
        //    return "<span class='text-muted'>{$item['data-two']}</span>";
        //}, $placeholderItems);
    @endphp


    {{-- Draggable Grid Table --}}
    @livewire('blocks.dragdrop.show-mult-drag-data', [
    'items' => $placeholderItems,
    'dataOneHtml' => $dataOneHtml,
    'dataTwoHtml' => $dataTwoHtml,
    'editCallback' => 'openEditCategoriesModal'])


    {{-- Add Category Button --}}
    <x-buttons.dotted-btn-open-modal label="Add Category" modal="addCategory" :disabled="false" />
</section>


@endsection

{{-- Add / Edit Category Dialogue --}}
<x-modals.edits_dialogues.edit-categories />
<x-modals.create_dialogues.add-category />