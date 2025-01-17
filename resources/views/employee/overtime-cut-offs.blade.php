@extends('components.layout.employee.layout', ['description' => 'Overtime Cut-offs', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Overtime Cut-Offs</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/leaves.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/leaves.css'])

@endPushOnce
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{__('Overtime Cut-Offs')}}
    </x-slot:heading>

    <x-slot:description>
        {!! __('Manage overtime cut-offs per each request of ').
            '<span class="text-primary fw-semibold">' 
                .auth()->user()->account->jobTitle->jobFamily->job_family_name. 
            '</span>'.
            __(' employees here.') !!}
    </x-slot:description>
</x-headings.main-heading>

<section class="my-2">
    <livewire:employee.tables.overtime-request-cutoffs-table />
</section>

@endsection