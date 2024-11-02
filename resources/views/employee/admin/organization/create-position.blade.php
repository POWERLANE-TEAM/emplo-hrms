{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Department</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('pre-scripts')
    <script src="//unpkg.com/alpinejs" defer></script> 
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

<x-headings.header-link heading="Create New Department" description="Create a new department of the company."
    label="Bulk Creation" nonce="{{ $nonce }}" href="{{ route($guard . '.accounts') }}" />

    <x-sub-navs.tabular-nav :guard="$guard" :items="[
    ['title' => 'Department', 'route' => 'create-dept', 'active' => false],
    ['title' => 'Position', 'route' => 'create-position', 'active' => true],
]" />

@php
    $qualificationOptions = ['High Priority', 'Medium Priority', 'Low Priority']; // Replace this with data fetched from db
    $customOptions = ['Custom Option 1', 'Custom Option 2', 'Custom Option 3', 'Custom Option 4']; // Replace this with data fetched from db
@endphp


@livewire('show-draggable-data', ['items' => $customOptions])

@livewire('qualification-input', [
    'label' => 'Add Qualification',
    'required' => true,
    'id' => 'qualification-input',
    'name' => 'qualification',
    'options' => $qualificationOptions
])


@endsection

<x-modals.edit-draggable-data></x-modals.edit-draggable-data>