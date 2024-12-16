@use ('Illuminate\View\ComponentAttributeBag')


<div>
    @include('livewire.applicant.application.application-wizard-progress-bar')
    <div class="d-contents">
        <section class="container " aria-label="Application Additional Details">

            <div class="my-3 col-md-8 mx-auto">

                <div>

                    {{-- <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
                        <div class="flex-1-sm-grow">
                            <x-form.boxed-input-text id="applicant-first-name" label="{{ __('First Name') }}"
                                name="applicant.name.firstName" type="list" list="applicant-names"
                                autocomplete="given-name"
                                class=" {{ $errors->has('applicant.name.firstName') ? 'is-invalid' : '' }}"
                                :nonce="$nonce" placeholder="Johny" required>

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-first-name-feedback',
                                        'message' => $errors->first('applicant.name.firstName'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-input-text>
                        </div>

                        <div class="flex-1-sm-grow">
                            <x-form.boxed-input-text id="applicant-middle-name" label="{{ __('Middle Name') }}"
                                name="applicant.name.middleName" autocomplete="additional-name" type="list"
                                list="applicant-names"
                                class=" {{ $errors->has('applicant.name.middleName') ? 'is-invalid' : '' }}"
                                :nonce="$nonce" placeholder="Doe">

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-middle-name-feedback',
                                        'message' => $errors->first('applicant.name.middleName'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-input-text>
                        </div>

                    </div> --}}


                    {{-- <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
                        <div class="flex-1">
                            <x-form.boxed-email id="applicant-email" label="Email Address" name="applicant.email"
                                autocomplete="email" :nonce="$nonce"
                                class=" {{ $errors->has('applicant.email') ? 'is-invalid' : '' }}" required>

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-email-feedback',
                                        'message' => $errors->first('applicant.email'),
                                    ])
                                </x-slot:feedback>
                                </x-form.email>
                        </div>
                        <div class="flex-1">
                            <x-form.phone label="Contact Number" id="aaplicant-mobile-num" :boxed="true"
                                list="applicant-contact-list"
                                class=" {{ $errors->has('applicant.mobileNumber') ? 'is-invalid' : '' }}"
                                name="applicant.mobileNumber" :nonce="$nonce" required>

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-mobile-num-feedback',
                                        'message' => $errors->first('applicant.mobileNumber'),
                                    ])
                                </x-slot:feedback>
                            </x-form.phone>
                        </div>
                    </div> --}}

                    <section class="present-address">
                        <x-headings.section-title title="{{ __('Present Address') }}" :isNextSection="true" />

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3 ">
                                <x-form.boxed-dropdown
                                    id="present_region"
                                    label="{{ __('Region') }}"
                                    wire:model.live="address.presentRegion"

                                    :nonce="$nonce"
                                    :required="true"
                                    :options="$presentAddressFields['regions']"

                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'present_region-feedback',
                                        'message' => $errors->first('address.presentRegion'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>
                                {{-- @error('address.presentRegion')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror --}}


                            </div>

                            @php
                            $isPresentRegionNcr = $this->address['presentRegion'] === '13';
                        @endphp

                            <div class="col-12 col-md-6 col-lg-3"         >
                                <x-form.boxed-dropdown
                                    id="present_province"
                                    label="{{ __('Province') }}"
                                    :wire:model.live="when(!$isPresentRegionNcr, 'address.presentProvince')"
                                    :nonce="$nonce"
                                    :required="when(!$isPresentRegionNcr, 'required')"
                                    :disabled="when($isPresentRegionNcr, true)"
                                    :options="$presentAddressFields['provinces']"

                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'present_province-feedback',
                                        'message' => $errors->first('address.presentProvince'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>


                            <div class="col-12 col-md-6 col-lg-3 ">
                                <x-form.boxed-dropdown
                                    id="present_city"
                                    label="{{ __('City / Municipality') }}"
                                    wire:model.live="address.presentCity"
                                    :nonce="$nonce"
                                    :required="true"
                                    :options="$presentAddressFields['cities']"

                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'present_city-feedback',
                                        'message' => $errors->first('address.presentCity'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown
                                    id="present_barangay"
                                    label="{{ __('Barangay') }}"
                                    wire:model.blur="address.presentBarangay"
                                    :nonce="$nonce"
                                    :required="true"
                                    :options="$presentAddressFields['barangays']"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'present_barangay-feedback',
                                        'message' => $errors->first('address.presentBarangay'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <x-form.boxed-input-text
                                    id="present_address"
                                    label="{{ __('Present Home Address') }}"
                                    wire:model.live="address.presentAddress"
                                    :nonce="$nonce"
                                    required
                                    placeholder="{{ __('Room/Floor/Unit No. & Bldg. Name | House/Lot & Blk. No. | Street Name | Subdivision') }}"
                                    :showPlaceholderOnLabel="true"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'present_address-feedback',
                                        'message' => $errors->first('address.presentAddress'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-input-text>


                            </div>
                        </div>
                    </section>

                    <section class="permanent-address">
                        <x-headings.section-title title="{{ __('Permanent Address') }}" />

                        <x-form.checkbox container_class="" :nonce="$nonce"  id="sameAddressCheck"
                        wire:model="samePresentAddressChckBox.checked"
                        wire:click="useSameAsPresentAddress"
                        :data-comp-id="$this->__id"
                        class="checkbox checkbox-primary same-present-address" >

                            <x-slot:label>
                                {{ __('Same as present address') }}
                            </x-slot:label>

                            <x-slot:feedback>
                                @include('components.form.input-feedback', [
                                    'feedback_id' => 'sameAddressCheck-feedback',
                                    'message' => $errors->first('samePresentAddressChckBox.checked'),
                                ])
                            </x-slot:feedback>
                        </x-form.checkbox>


                        <div class="row">
                            <div class="flex-1-sm-grow">
                                <x-form.boxed-dropdown
                                    id="permanent_region"
                                    label="{{ __('Region') }}"
                                    wire:model.live="address.permanentRegion"
                                    :nonce="$nonce"
                                    :required="true"
                                    :options="$permanentAddressFields['regions']"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'permanent_region-feedback',
                                        'message' => $errors->first('address.permanentRegion'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>

                            @php
                                $isPermaRegionNcr = $this->address['permanentRegion'] === '13';
                            @endphp

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown
                                    id="permanent_province"
                                    label="{{ __('Province') }}"
                                    :wire:model.live="when(!$isPermaRegionNcr || !$this->samePresentAddressChckBox['checked'], 'address.permanentProvince')"
                                    :nonce="$nonce"
                                    :required="when(!$isPermaRegionNcr, 'required')"
                                    :disabled="when($isPermaRegionNcr, true)"
                                    :options="$permanentAddressFields['provinces']"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'permanent_province-feedback',
                                        'message' => $errors->first('address.permanentProvince'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>

                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown
                                    id="permanent_city"
                                    label="{{ __('City / Municipality') }}"
                                    wire:model.live="address.permanentCity"
                                    :nonce="$nonce"
                                    :required="true"
                                    :options="$permanentAddressFields['cities']"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'permanent_city-feedback',
                                        'message' => $errors->first('address.permanentCity'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown
                                    id="permanent_barangay"
                                    label="{{ __('Barangay') }}"
                                    wire:model.live="address.permanentBarangay"
                                    :nonce="$nonce"
                                    :required="true"
                                    :options="$permanentAddressFields['barangays']"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'permanent_barangay-feedback',
                                        'message' => $errors->first('address.permanentBarangay'),
                                    ])
                                </x-slot:feedback>
                                </x-form.boxed-dropdown>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <x-form.boxed-input-text
                                    id="permanent_address"
                                    label="{{ __('Permanent Home Address') }}"
                                    wire:model="address.permanentAddress"
                                    :nonce="$nonce"
                                    :required="true"
                                    placeholder="{{ __('Room/Floor/Unit No. & Bldg. Name | House/Lot & Blk. No. | Street Name | Subdivision') }}"
                                    :showPlaceholderOnLabel="true"
                                >
                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'permanent_address-feedback',
                                        'message' => $errors->first('address.permanentAddress'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-input-text>

                            </div>
                        </div>
                    </section>

                </div>

            </div>

        </section>
        @include('livewire.applicant.application.application-wizard-nav-btn')
    </div>
</div>
