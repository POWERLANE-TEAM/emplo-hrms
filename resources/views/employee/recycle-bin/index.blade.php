@extends('components.layout.employee.layout', ['nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Recycle Bin</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    {{-- --}}
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Recycle Bin')}}
    </x-slot:heading>

    <x-slot:description>
        <p><span class="mb-0">Restore or permanently delete your previously deleted files.</p>
    </x-slot:description>
</x-headings.main-heading>

<!-- BACK-END REPLACE TABLE: Recycle Bin per user -->

@endsection