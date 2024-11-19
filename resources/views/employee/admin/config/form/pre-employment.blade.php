{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Pre-Employment Requirements', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Configure Pre-Employment Requirements</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/drag-and-drop.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}

{{-- Body/Content Section --}}
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Configure Forms') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Set up and configure all of the forms within the system.') }}
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.forms-tab-navs')

<livewire:admin.config.form.pre-employment />

@endsection