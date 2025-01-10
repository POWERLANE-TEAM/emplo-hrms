@extends('components.layout.employee.layout', ['nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Profile</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

<div class="row pb-3">
    <div class="col-md-8">
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'ps-2 pt-2 pb-2 ms-n1'])" :overrideContainerClass="true" class="fs-2 fw-bold mb-2 text-secondary-emphasis">
            <x-slot:heading>
                {{ __('Profile Information') }}
            </x-slot:heading>
        </x-headings.main-heading>
    </div>

    <div class="col-md-4 text-end d-flex justify-content-end align-items-center">
        <div>
            <a href="{{ route($routePrefix . '.profile.edit') }}" class="btn btn-lg btn-outline-primary">
                <i data-lucide="pen-line" class="icon icon-large me-2"></i>
                {{ __('Edit Profile') }}
            </a>
        </div>
    </div>
</div>

<livewire:profile.information />

@endsection