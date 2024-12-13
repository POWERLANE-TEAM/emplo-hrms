
<div>
<section class="mx-2">
    <form>
        {{-- Section: Personal Information --}}
        <section>
            <x-headings.section-title title="Personal Information" />

            {{-- First, Middle, Last Name --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-input-text id="first_name" label="First Name" name="first_name" :nonce="$nonce"
                        :required="true" placeholder=""/>
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
                    <x-form.boxed-email id="email" label="Email Address" name="email" autocomplete="email" :nonce="$nonce" class=" {{ $errors->has('email') ? 'is-invalid' : '' }}" :required="true" placeholder="">

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
                    <x-form.boxed-input-text id="contact_no" label="Contact Number" name="contact_no" :nonce="$nonce" :required="true" placeholder=""/>
                </div>
            </div>

            {{-- Present and Permanent Addresses --}}
            <div class="row">
                <div class="col">
                    <x-form.boxed-input-text id="present_address" label="Present Address" name="present_address" :nonce="$nonce" :required="true" placeholder=""/>
                </div>
                <div class="col">
                    <x-form.boxed-input-text id="perm_address" label="Permanent Address" name="perm_address" :nonce="$nonce" :required="true" placeholder=""/>
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

        <aside class="pl-3">
    <x-info_panels.note 
    note="Some information shown on the profile information cannot be edited."
    />
</aside>

<x-buttons.main-btn id="create_acc" label="Create Account" name="create_acc" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />

    </form>
</section>
</div>