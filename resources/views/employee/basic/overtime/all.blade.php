@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>All Summary Forms</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/leaves.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/leaves.css'])

@endPushOnce
@section('content')



<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Overtime Summary Forms')}}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('Manage your overtime summary forms and hours.') }}</p>
    </x-slot:description>
</x-headings.main-heading>


<section class="my-2">
    <!-- BACK-END REPLACE: Table of all OT Summary Forms. Highlight the current paayroll period. -->
</section>

@endsection