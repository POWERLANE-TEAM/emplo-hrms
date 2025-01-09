{{-- 
I need a modal instead that shows the account 
hass been successfully created and the random
generated password was sent to email. --Carl
--}}

@props(['success' => __('Account created successfully.')])

<section>
    <div class="mx-2">
        <form enctype="multipart/form-data" novalidate>
            {{-- Section: Personal Information --}}
            <section>
                <x-headings.section-title title="{{ __('Personal Information') }}" />

                {{-- First, Middle, Last Name --}}
                <div class="row">
                    <div class="col">
                        <x-form.boxed-input-text
                            id="first_name"
                            label="{{ __('First Name') }}"
                            name="form.firstName"
                            :nonce="$nonce" 
                            :required="true"
                            placeholder="Ricardo"
                        />
                        @error('form.firstName')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-input-text 
                            id="middle_name" 
                            label="{{ __('Middle Name') }}" 
                            name="form.middleName" 
                            :nonce="$nonce"
                            :required="false" 
                            placeholder="Nicanor"
                        />
                        @error('form.middleName')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-input-text 
                            id="last_name" 
                            label="{{ __('Last Name') }}" 
                            name="form.lastName" 
                            :nonce="$nonce"
                            :required="true" 
                            placeholder="Dalisay"
                        />
                        @error('form.lastName')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Email Address, Contact Number --}}
                <div class="row">
                    <div class="col">
                        <x-form.boxed-email 
                            id="email" 
                            label="{{ __('Email Address') }}" 
                            name="form.email" 
                            autocomplete="email" 
                            :nonce="$nonce"
                            class=" {{ $errors->has('form.email') ? 'is-invalid' : '' }}" 
                            :required="true" 
                            placeholder="ricardodalisay@imortal.org">
                            @include('components.form.input-feedback', [
                                'feedback_id' => 'email',
                                'message' => $errors->first('form.email'),
                            ])                               
                        </x-form.boxed-email>
                    </div>

                    <div class="col">
                        <x-form.boxed-input-text 
                            id="contact_no" label="{{ __('Contact Number') }}" 
                            name="form.contactNumber" 
                            :nonce="$nonce"
                            :required="true" 
                            placeholder="09556677890"
                        />
                        @error('form.contactNumber')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Birthdate, Sex at birth, Civil Status --}}
                <div class="row">
                    <div class="col">
                        <x-form.boxed-date 
                            id="birthdate" 
                            label="{{ __('Birthdate') }}" 
                            name="form.birthDate" 
                            :nonce="$nonce"
                            :required="true" 
                            placeholder="Birthdate"
                        />
                        @error('form.birthDate')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="sex" 
                            label="{{ __('Sex at birth') }}" 
                            name="form.sex" 
                            :nonce="$nonce" 
                            :required="true"
                            :options="$this->sexes"
                            placeholder="Select type">
                        </x-form.boxed-dropdown>
                        @error('form.sex')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="civil_status" 
                            label="{{ __('Civil Status') }}" 
                            name="form.civilStatus" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->civilStatuses"
                            placeholder="Select type">
                        </x-form.boxed-dropdown>
                        @error('form.civilStatus')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            <section>
                <x-headings.section-title title="{{ __('Present Address') }}" :isNextSection="true" />

                <div class="row">
                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="present_region" 
                            label="{{ __('Region') }}" 
                            wire:model.live="form.presentRegion"
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$presentFields['regions']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.presentRegion')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="present_province" 
                            label="{{ __('Province') }}" 
                            wire:model.live="form.presentProvince"
                            :nonce="$nonce" 
                            :required="true"
                            :options="$presentFields['provinces']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.presentProvince')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="present_city" 
                            label="{{ __('City / Municipality') }}" 
                            wire:model.live="form.presentCity" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$presentFields['cities']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.presentCity')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="present_barangay" 
                            label="{{ __('Barangay') }}" 
                            wire:model.blur="form.presentBarangay" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$presentFields['barangays']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.presentBarangay')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <x-form.boxed-input-text 
                            id="present_address" 
                            label="{{ __('Present Home Address') }}" 
                            wire:model.blur="form.presentAddress" 
                            :nonce="$nonce"
                            :required="true" 
                            placeholder="{{ __('Room/Floor/Unit No. & Bldg. Name | House/Lot & Blk. No. | Street Name | Subdivision') }}"
                        />
                        @error('form.presentAddress')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            <section>
                <x-headings.section-title title="{{ __('Permanent Address') }}" />

                @if($samePresentAddressChckBox['shown'])
                    <div class="form-check mb-3">
                        <input class="form-check-input"
                            type="checkbox"
                            id="sameAddressCheck"
                            wire:model="samePresentAddressChckBox.checked"
                            wire:click="useSameAsPresentAddress"
                        >
                        <label class="form-check-label" for="sameAddressCheck">
                            {{ __('Same as present address') }}
                        </label>
                    </div>
                @endif            

                <div class="row">
                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="permanent_region" 
                            label="{{ __('Region') }}" 
                            wire:model.live="form.permanentRegion" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$permanentFields['regions']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.permanentRegion')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="present_province" 
                            label="{{ __('Province') }}" 
                            wire:model.live="form.permanentProvince" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$permanentFields['provinces']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.permanentProvince')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="permanent_city" 
                            label="{{ __('City / Municipality') }}" 
                            wire:model.live="form.permanentCity" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$permanentFields['cities']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.permanentCity')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="permanent_barangay" 
                            label="{{ __('Barangay') }}" 
                            wire:model.live="form.permanentBarangay" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$permanentFields['barangays']"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.permanentBarangay')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <x-form.boxed-input-text 
                            id="permanent_address" 
                            label="{{ __('Permanent Home Address') }}" 
                            wire:model.live="form.permanentAddress" 
                            :nonce="$nonce"
                            :required="true" 
                            placeholder="{{ __('Room/Floor/Unit No. & Bldg. Name | House/Lot & Blk. No. | Street Name | Subdivision') }}"
                        />
                        @error('form.permanentAddress')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>


            {{-- Section: Work Information --}}
            <section>
                <x-headings.section-title title="{{ __('Work Information') }}" :isNextSection="true" />
                {{-- Area, Job Family, Job Level, Job Title --}}
                <div class="row">
                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="area" 
                            label="{{ __('Area / Branch') }}" 
                            name="form.area" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->areas"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.area')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="dept" 
                            label="{{ __('Job Family') }}" 
                            name="form.jobFamily" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->jobFamilies"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.jobFamily')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="job_level" 
                            label="{{ __('Job Level') }}" 
                            name="form.jobLevel" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->jobLevels"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.jobLevel')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="job_title" 
                            label="{{ __('Job Title') }}" 
                            name="form.jobTitle" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->jobTitles"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.jobTitle')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Role, Employment Status, Schedule/Shift --}}
                <div class="row">
                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="role" 
                            label="{{ __('Role') }}" 
                            name="form.role" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->formattedRoles"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.role')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="emp_status" 
                            label="{{ __('Employment Status') }}" 
                            name="form.employmentStatus" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->employmentStatuses"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.employmentStatus')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-dropdown 
                            id="shift" 
                            label="{{ __('Schedule / Shift') }}" 
                            wire:model.live="form.shift" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->shifts"
                        >
                        </x-form.boxed-dropdown>
                        @error('form.shift')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Section: Statutory Requirements --}}
            <section>
                <x-headings.section-title title="{{ __('Statutory Requirements') }}" :isNextSection="true" />
                
                {{-- SSS, PhilHealth TIN, PAGIBIG --}}
                <div class="row">
                    <div class="col">
                        <x-form.boxed-input-text
                            id="sss_no" 
                            label="{{ __('SS Number') }}" 
                            name="form.sss" 
                            :nonce="$nonce"
                            :required="false" 
                            placeholder=""
                        />

                        @error('form.sss')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-input-text 
                            id="philhealth_no" 
                            label="{{ __('PhilHealth ID Number (PIN)') }}" 
                            name="form.philhealth" 
                            :nonce="$nonce"
                            :required="false" 
                            placeholder=""
                        />
                        @error('form.philhealth')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-input-text 
                            id="tin" 
                            label="{{ __('Taxpayer ID Number (TIN)') }}" 
                            name="form.tin" 
                            :nonce="$nonce"
                            :required="false" 
                            placeholder=""
                        />
                        @error('form.tin')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <x-form.boxed-input-text 
                            id="pagibig_no" 
                            label="{{ __('Pag-IBIG MID Number') }}" 
                            name="form.pagibig" 
                            :nonce="$nonce"
                            :required="false" 
                            placeholder=""
                        />
                        @error('form.pagibig')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </section>
        </form>
    </div>

    <aside class="pl-3">
        <x-info_panels.note 
        note="{{ __('The system will automatically generate the password and employee ID, which will be sent to the provided email address.') }}"
        />
    </aside>

    <x-buttons.main-btn 
        id="create_acc" 
        label="{{ __('Create Account') }}" 
        wire:click="save" 
        target="save"
        :nonce="$nonce"
        :disabled="false" 
        class="w-25" 
        loading="Creating..." 
    />

    <span 
        x-data="{ successAlert: false }"
        x-cloak
        x-on:account-created.window="successAlert = true; setTimeout(() => { successAlert = false }, 2000)"
        x-show.transition.out.opacity.duration.1500ms="successAlert"
        x-transition:leave.opacity.duration.1500ms
        x-show="successAlert"
        class="fw-bold text-success"
    >
        {{ $success }}
    </span>
</section>