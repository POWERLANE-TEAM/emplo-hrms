{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Account</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
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

<x-headings.header-link heading="Create an Account" description="Kindly fill up the following information."
    label="Bulk Creation" nonce="{{ $nonce }}" href="{{ route($guard . '.accounts') }}" />


{{--
|--------------------------------------------------------------------------
| Account Form
|--------------------------------------------------------------------------
--}}

<section class="mx-2">
    <form>
        {{-- Section: Personal Information --}}
        <section>
            <x-headings.section-title title="Personal Information" />

            {{-- First, Middle, Last Name --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-input-text id="first_name" label="First Name" name="first_name" :nonce="$nonce"
                        :required="true" placeholder="Biella"/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="middle_name" label="Middle Name" name="middle_name" :nonce="$nonce"
                        :required="false" placeholder=""/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="last_name" label="Last Name" name="last_name" :nonce="$nonce"
                        :required="true" placeholder=""/>
                </div>
            </div>

            {{-- Email Address, Contact Number --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-email id="email" label="Email Address" name="email" autocomplete="email" :nonce="$nonce"
                        class=" {{ $errors->has('email') ? 'is-invalid' : '' }}" :required="true" placeholder="">

                        <!-- For validation. Uncomment if needed.
                            <x-slot:feedback>
                            @include('components.form.input-feedback', [
                            'feedback_id' => 'signUp-email-feedback',
                            'message' => $errors->first('email'),
                            ])
                        </x-slot:feedback> -->

                    </x-form.boxed-email>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="contact_no" label="Contact Number" name="contact_no" :nonce="$nonce"
                        :required="true" placeholder=""/>
                </div>
            </div>

            {{-- Present and Permanent Addresses --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-input-text id="present_address" label="Present Address" name="present_address" :nonce="$nonce"
                        :required="true" placeholder=""/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="perm_address" label="Permanent Address" name="perm_address" :nonce="$nonce"
                        :required="true" placeholder=""/>
                </div>
            </div>

            {{-- Birthdate, Sex at birth, Civil Status, Educational Attainment --}}
            <div class="row">
                <div class="col">
                <x-form.boxed-date id="birthdate" label="Birthdate" name="birthdate" :nonce="$nonce"
                    :required="true" placeholder="Birthdate"/>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="sex" 
                        label="Sex at birth" 
                        name="sex" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            'male' => 'Male', 
                            'female' => 'Female', 
                            'other' => 'Other', 
                            'prefer_not_to_say' => 'Prefer not to say'
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="civil_status" 
                        label="Civil Status" 
                        name="civil_status" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            'single' => 'Single', 
                            'married' => 'Married', 
                            'widowed' => 'Widowed', 
                            'divorced' => 'Divorced', 
                            'separated' => 'Separated'
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="educ_attain" 
                        label="Educational attainment" 
                        name="educ_attain" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            'none' => 'None', 
                            'elem' => 'Elementary School', 
                            'highschool' => 'High School', 
                            'shs' => 'Senior High School', 
                            'undergraduate' => 'Undergraduate', 
                            'graduate' => 'Graduate Degree'
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>
            </div>
        </section>


        {{-- Section: Work Information --}}
        <section>
            <x-headings.section-title title="Work Information" :isNextSection="true" />
            
            {{-- Dept, Job Position, Salary --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-dropdown 
                        id="dept" 
                        label="Department" 
                        name="dept" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            // Add here the list
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="job_position" 
                        label="Job Position" 
                        name="job_position" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            // Add here the list
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="salary" 
                        label="Base Salary" 
                        name="salary" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            // Add here the list
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>
            </div>

            {{-- Role, Employment Status, Schedule/Shift --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-dropdown 
                        id="role" 
                        label="Role" 
                        name="role" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            // Add here the list
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="emp_status" 
                        label="Employment Status" 
                        name="emp_status" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            // Add here the list
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>

                <div class="col">
                    <x-form.boxed-dropdown 
                        id="shift" 
                        label="Schedule / Shift" 
                        name="shift" 
                        :nonce="$nonce" 
                        :required="true" 
                        :options="[
                            // Add here the list
                        ]"
                        placeholder="Select type">
                    </x-form.boxed-dropdown>
                </div>
            </div>
        </section>

        {{-- Section: Work Information --}}
        <section>
            <x-headings.section-title title="Identification Numbers" :isNextSection="true" />
            
            {{-- SSS, Cedula / CTC, PhilHealth --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-input-text id="sss_no" label="SSS" name="sss_no" :nonce="$nonce"
                        :required="false" placeholder=""/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="cedula_no" label="Cedula/CTC" name="cedula_no" :nonce="$nonce"
                        :required="false" placeholder=""/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="philhealth_no" label="PhilHealth" name="philhealth_no" :nonce="$nonce"
                        :required="false" placeholder=""/>
                </div>
            </div>

            {{-- TIN, PAGIBIG --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-input-text id="tin_no" label="TIN" name="tin_no" :nonce="$nonce"
                        :required="false" placeholder=""/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="pagibig_no" label="Cedula/CTC" name="pagibig_no" :nonce="$nonce"
                        :required="false" placeholder=""/>
                </div>
            </div>
        </section>
    </form>
</section>

<aside class="pl-3">
    <x-info_panels.note 
    note="The system will automatically generate the password and employee ID, which will be sent to the employee's inputted email address."
    />
</aside>

<x-buttons.main-btn id="create_acc" label="Create Account" name="create_acc" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
@endsection



{{--
|--------------------------------------------------------------------------
| List of IDs and Names
| *Both ID and Name attributes share the same naming convention.
|--------------------------------------------------------------------------
| Element | ID/Name | Description
|--------------------------------------------------------------------------
| Announcement Title | announcement_title | Title of the announcement
| Description | announcement_desc | Main description field
| Post Announcement | post_announcement | Button to post announcement
|--------------------------------------------------------------------------
--}}