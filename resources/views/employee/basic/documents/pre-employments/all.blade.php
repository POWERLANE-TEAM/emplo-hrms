@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Files | Pre-employment Requirements</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/employee-info.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Pre-Employment Files') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Manage and update all of your documents.') }}
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.file-manager')

<!-- BACK-END TABLE NOTE:
    The employee id should be passed to: training/records.blade.php -->
    <livewire:employee-documents-table :employee="$employee" />

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('perPageDispatcher', () => ({
                updatePerPage() {
                    let select = document.getElementById('table-perPage');
                    let currentValue = parseInt(select.value);
                    let newValue = currentValue + 5;
                    let options = Array.from(select.options).map(option => parseInt(option.value));
                    if (options.includes(newValue)) {
                        select.value = newValue;
                        select.dispatchEvent(new Event('change'));
                    }
                }
            }));
        });
    </script>

@endsection
