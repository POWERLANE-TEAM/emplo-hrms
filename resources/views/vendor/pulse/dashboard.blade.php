@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

@section('head')
<title>Laravel Pulse</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

<!-- Internal stylesheet to not affect other styles.
 Solely reserved for this page due to Pulse being heavily driven by Tailwind,
 resulting to conflict styles with Bootstrap. Still have issues. -->

<style>
    .min-h-screen.bg-gray-50 {
        background-color: #fff;
    }

    .dark .bg-gray-50 {
        background-color: #212529 !important;
    }

    .search {
        border-radius: 50rem !important;
    }

    .site-name {
        gap: 0 1rem;
        :has(>*>[class$="logo"]) {
            :has(>[class$="logo"]) {
                padding: 0 !important;
                transform: translateY(-5%);
                background-color: var(--main-white);
                border-radius: min(0.5cqh, 0.75rem);
            }
        }
    }
}


</style>

<div class="pt-3 pr-2">
    <x-pulse class="bg-white">
        <livewire:pulse.servers cols="full" />
        <livewire:pulse.usage cols="4" rows="2" />
        <livewire:pulse.queues cols="4" />
        <livewire:pulse.cache cols="4" />
        <livewire:pulse.slow-queries cols="8" />
        <livewire:pulse.exceptions cols="6" />
        <livewire:pulse.slow-requests cols="6" />
        <livewire:pulse.slow-jobs cols="6" />
        <livewire:pulse.slow-outgoing-requests cols="6" />
    </x-pulse>
</div>

@endsection