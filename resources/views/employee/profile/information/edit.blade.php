@extends('components.layout.employee.layout', ['nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Profile</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

<div class="row pb-3">
    <div class="col-md-8 d-flex align-items-center">
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'ps-2 pt-2 ms-n1'])" :overrideContainerClass="true" :overrideClass="true" class="fs-2 fw-bold text-secondary-emphasis">
            <x-slot:heading>
                {{ __('Edit Profile Information') }}
            </x-slot:heading>
        </x-headings.main-heading>
    </div>

    <div class="col-md-4 text-end d-flex justify-content-end align-items-center">
        <div>
            <a href="{{ route($routePrefix . '.profile.index') }}" class="hover-opacity" data-bs-toggle="tooltip" title="Close">
                <i data-lucide="circle-x" class="text-black-50 icon icon-xlarge"></i>
            </a>
        </div>
    </div>
</div>

<livewire:profile.edit-information-form />



@endsection