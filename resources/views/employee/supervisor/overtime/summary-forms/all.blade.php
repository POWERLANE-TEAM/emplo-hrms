@extends('components.layout.employee.layout', ['description' => 'Overtime Summary', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')
@use ('App\Enums\UserPermission')

@section('head')
<title>Overtime Summaries</title>
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
        {{__('Overtime Summary Forms')}}
    </x-slot:heading>

    <x-slot:description>
        @if (auth()->user()->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME_REQUEST))
            {{ __('View and manage overtime summary forms of every employees here.') }}
        @else
            {!! __('Manage overtime summary forms per each request of ').
                '<span class="text-primary fw-semibold">' 
                    .auth()->user()->account->jobTitle->jobFamily->job_family_name. 
                '</span>'.
                __(' employees here.') !!}
        @endif
    </x-slot:description>
</x-headings.main-heading>

<section class="my-2">
    <livewire:employee.tables.overtime-request-summaries-table />
</section>

@endsection