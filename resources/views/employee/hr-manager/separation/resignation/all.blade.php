@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Separation</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/separation.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/separation.css'])

@endPushOnce
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Resignation Letters')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('Manage and approve submitted resignation letters.') }}</p>
    </x-slot:description>
</x-headings.main-heading>

<!-- BACK-END REPLACE: TABLE FOR ALL RESIGNATION LETTERS -->
@endsection
