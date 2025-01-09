@extends('components.layout.employee.layout', ['description' => 'Overtime CutOffs', 'nonce' => $nonce])
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

<section class="row">
    <div class="col-6">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{__('Overtime Cut-Offs')}}
            </x-slot:heading>
        
            <x-slot:description>
                <p>{{ __('Manage your overtime cut-off summary forms and requests.') }}</p>
            </x-slot:description>
        </x-headings.main-heading>
    </div>
    <div class="col-6 d-flex justify-content-end align-items-center">
        <button onclick="openModal('requestOvertimeModal')" class="btn btn-primary">
        <i data-lucide="plus-circle" class="icon icon-large me-2"></i>{{ __('Request Overtime') }}</button>
    </div>
    <livewire:employee.overtimes.basic.request-overtime />
    <livewire:employee.overtimes.basic.edit-overtime-request />
</section>

<section class="my-2">
    <livewire:employee.tables.basic.overtime-summaries-table />
</section>

@endsection