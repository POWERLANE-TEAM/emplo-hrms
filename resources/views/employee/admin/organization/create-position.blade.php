{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Position</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('pre-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}



{{-- Body/Content Section --}}
@section('content')

<x-headings.header-link heading="Create New Position" description="Create a new job position." label="Bulk Creation"
    nonce="{{ $nonce }}" href="{{ route($guard . '.accounts') }}">
</x-headings.header-link>

@include('components.includes.tab_navs.org-tab-navs')


{{--
* |--------------------------------------------------------------------------
* | JOB POSITION FORM
* |--------------------------------------------------------------------------
--}}

<x-info_panels.callout type="info"
    description="Ensure the department is added before assigning a position, otherwise it will not appear." note="true">
</x-info_panels.callout>

<section class="mx-2">
    {{-- Job Position Information --}}
    <section>
        {{-- Job Position --}}
        <div class="row">
            <div class="col">
                <x-form.boxed-input-text id="job_position" label="Job Position Title" name="job_position" :nonce="$nonce"
                :required="true" placeholder="Enter job position" />
            </div>
        </div>

        {{-- Department --}}
        <div class="row">
            <div class="col">
                <x-form.boxed-dropdown id="dept" label="Department" name="sex" :nonce="$nonce" :required="true"
                    :options="[
        'accounting' => 'Accounting',
        'hr' => 'Human Resource',
        'other' => 'Other',
    ]"
                placeholder="Select department">
                </x-form.boxed-dropdown>
            </div>
        </div>

        {{-- Textarea field for: Job Position Description --}}
        <x-form.boxed-textarea id="job_desc" label="Job Position Description" name="job_desc" :nonce="$nonce" :rows="6"
            :required="true" placeholder="Enter description for the job position..." />
    </section>


    {{-- Qualifications Section --}}
    <section>

        <x-headings.form-snippet-intro label="Qualification" nonce="yourNonce" required="true"
            description="This is an optional description.">

            <x-tooltips.modal-tooltip icon="help-circle" color="text-info" modalId="editModalId" />

        </x-headings.form-snippet-intro>

        {{-- Placeholder datas. Need to be mounted properly from the db. --}}
        @php
            $qualificationOptions = ['High Priority', 'Medium Priority', 'Low Priority'];
            $placeholderItems = [
                ['text' => 'Custom Option 1', 'priority' => 'High Priority'],
                ['text' => 'Custom Option 2', 'priority' => 'Medium Priority'],
                ['text' => 'Custom Option 3', 'priority' => 'Low Priority'],
                ['text' => 'Custom Option 4', 'priority' => 'Medium Priority']
            ]; // Replace this with data fetched from db
            $customOptions = ['Custom Option 1', 'Custom Option 2', 'Custom Option 3', 'Custom Option 4']; // Replace this with data fetched from db
        @endphp


        {{-- Grid Table of Qualifications --}}
        @livewire('blocks.dragdrop.show-qualifications', ['items' => $placeholderItems])


        {{-- Add Another Qualification Field --}}
        @livewire('blocks.inputs.qualification-input', [
    'label' => 'Add Qualification',
    'required' => true,
    'id' => 'qualification-input',
    'name' => 'qualification',
    'options' => $qualificationOptions
])
    </section>


    {{-- Submit Button --}}
    <section class="my-4">
        <x-buttons.main-btn id="create_position" label="Create Position" name="create_position" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
    </section>
</section>
@endsection

{{-- Edit Dialogue / About Qualification Modal --}}
<x-modals.edit-qualification />
<x-modals.informational.about-qualification />