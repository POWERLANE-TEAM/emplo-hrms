@use ('Illuminate\View\ComponentAttributeBag')


<div>
    @include('livewire.applicant.application.application-wizard-progress-bar')
    <div class="d-contents">
        <section class="container " aria-label="Application Additional Details">

            <div class="my-3 col-md-8 mx-auto">

                <div>

                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 ">

                            <x-form.boxed-dropdown id="civil_status" label="{{ __('Civil Status') }}" name="civilStatus"
                                :nonce="$nonce" :required="true" :options="$this->civilStatuses" placeholder="Select type">

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'civil_status-feedback',
                                        'message' => $errors->first('civilStatus'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-dropdown>


                        </div>


                    </div>




                    <section class="present-address">
                        <x-headings.section-title title="{{ __('Present Address') }}" :isNextSection="true" />

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3 ">
                                <x-form.boxed-dropdown id="present_region" wire:model.live="address.presentRegion"
                                    :nonce="$nonce" :required="true" autocomplete="home address-level1 country-name"
                                    ariaOwns="present-applicant-address-feedback" :options="$presentAddressFields['regions']">

                                    <x-slot:label>
                                        {{ __('Region') }}
                                        <x-html.inline-spinner target="address.presentRegion" />
                                    </x-slot:label>
                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'present_region-feedback',
                                            'message' => $errors->first('address.presentRegion'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>
                            </div>

                            @php
                                $isPresentRegionNcr = $this->address['presentRegion'] === '13';
                            @endphp

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown id="present_province"
                                    :wire:model.live="when(!$isPresentRegionNcr, 'address.presentProvince')"
                                    autocomplete="home address-level2" ariaOwns="present-applicant-address-feedback"
                                    :nonce="$nonce" :required="when(!$isPresentRegionNcr, 'required')" :disabled="when($isPresentRegionNcr, true)" :options="$presentAddressFields['provinces']">
                                    <x-slot:label>
                                        {{ __('Province') }}
                                        <x-html.inline-spinner :target="['address.presentProvince', 'address.presentRegion']" />
                                    </x-slot:label>
                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'present_province-feedback',
                                            'message' => $errors->first('address.presentProvince'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>


                            <div class="col-12 col-md-6 col-lg-3 ">
                                <x-form.boxed-dropdown id="present_city" label="{{ __('City / Municipality') }}"
                                    wire:model.live="address.presentCity" autocomplete="home address-level3"
                                    :nonce="$nonce" :required="true" ariaOwns="present-applicant-address-feedback"
                                    :options="$presentAddressFields['cities']">

                                    <x-slot:label>
                                        {{ __('City / Municipality') }}
                                        <x-html.inline-spinner :target="['address.presentCity', 'address.presentProvince']" />
                                    </x-slot:label>

                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'present_city-feedback',
                                            'message' => $errors->first('address.presentCity'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown id="present_barangay" wire:model.blur="address.presentBarangay"
                                    autocomplete="home address-level4" :nonce="$nonce" :required="true"
                                    ariaOwns="present-applicant-address-feedback" :options="$presentAddressFields['barangays']">

                                    <x-slot:label>
                                        {{ __('Barangay') }}
                                        <x-html.inline-spinner :target="['address.presentBarangay', 'address.presentCity']" />
                                    </x-slot:label>

                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'present_barangay-feedback',
                                            'message' => $errors->first('address.presentBarangay'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>


                            </div>
                        </div>

                        @include('components.form.input-feedback', [
                            'class' => 'd-none d-md-block',
                            'feedback_id' => 'present-applicant-address-feedback',
                            'message' =>
                                $errors->first('address.presentRegion') ??
                                ($errors->first('address.presentProvince') ??
                                    $errors->first('address.presentCity')),
                        ])



                        <div class="row">
                            <div class="col">
                                <x-form.boxed-input-text id="present_address" label="{{ __('Present Home Address') }}"
                                    wire:model.live.throttle.100ms="address.presentAddress"
                                    autocomplete="home street-address" :nonce="$nonce" required
                                    placeholder="{{ __('Room/Floor/Unit No. & Bldg. Name | House/Lot & Blk. No. | Street Name | Subdivision') }}"
                                    :showPlaceholderOnLabel="true">
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
                        <x-headings.section-title title="{{ __('Permanent Address') }}">
                            <x-html.inline-spinner target="samePresentAddressChckBox.checked" />
                        </x-headings.section-title>

                        <x-form.checkbox container_class="" :nonce="$nonce" id="sameAddressCheck"
                            wire:model="samePresentAddressChckBox.checked"
                            :wire:click="when($this->samePresentAddressChckBox['shown'], 'useSameAsPresentAddress')"
                            :data-comp-id="$this->__id" class="checkbox checkbox-primary same-present-address">

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
                                <x-form.boxed-dropdown id="permanent_region" label="{{ __('Region') }}"
                                    wire:model.live="address.permanentRegion"
                                    autocomplete="home address-level1 country-name"
                                    ariaOwns="permanent-applicant-address-feedback" :nonce="$nonce"
                                    :disabled="when($this->samePresentAddressChckBox['checked'], true)" :required="true" :options="$permanentAddressFields['regions']">

                                    <x-slot:label>
                                        {{ __('Region') }}
                                        <x-html.inline-spinner target="address.permanentRegion" />
                                    </x-slot:label>
                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
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
                                <x-form.boxed-dropdown id="permanent_province"
                                    :wire:model.live="when(!$isPermaRegionNcr || !$this->samePresentAddressChckBox['checked'], 'address.permanentProvince')"
                                    autocomplete="home address-level2" :nonce="$nonce"
                                    ariaOwns="permanent-applicant-address-feedback" :required="when(!$isPermaRegionNcr, 'required')"
                                    :disabled="when(
                                        $isPermaRegionNcr || $this->samePresentAddressChckBox['checked'],
                                        true,
                                    )" :options="$permanentAddressFields['provinces']">
                                    <x-slot:label>
                                        {{ __('Province') }}
                                        <x-html.inline-spinner :target="['address.permanentRegion', 'address.permanentProvince']" />
                                    </x-slot:label>
                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'permanent_province-feedback',
                                            'message' => $errors->first('address.permanentProvince'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>

                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown id="permanent_city" label="{{ __('City / Municipality') }}"
                                    wire:model.live="address.permanentCity" autocomplete="home address-level3"
                                    :nonce="$nonce" ariaOwns="permanent-applicant-address-feedback"
                                    :required="true" :disabled="when($this->samePresentAddressChckBox['checked'], true)" :options="$permanentAddressFields['cities']">
                                    <x-slot:label>
                                        {{ __('City / Municipality') }}
                                        <x-html.inline-spinner :target="['address.permanentCity', 'address.permanentProvince']" />
                                    </x-slot:label>

                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'permanent_city-feedback',
                                            'message' => $errors->first('address.permanentCity'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <x-form.boxed-dropdown id="permanent_barangay" label="{{ __('Barangay') }}"
                                    wire:model.blur="address.permanentBarangay" autocomplete="home address-level4"
                                    :nonce="$nonce" :required="true" :disabled="when($this->samePresentAddressChckBox['checked'], true)"
                                    ariaOwns="permanent-applicant-address-feedback" :options="$permanentAddressFields['barangays']">

                                    <x-slot:label>
                                        {{ __('Barangay') }}
                                        <x-html.inline-spinner :target="['address.permanentBarangay', 'address.permanentCity']" />
                                    </x-slot:label>
                                    <x-slot:feedback>
                                        @include('components.form.input-feedback', [
                                            'attributes' => new ComponentAttributeBag([
                                                'class' => 'd-block d-md-none',
                                            ]),
                                            'feedback_id' => 'permanent_barangay-feedback',
                                            'message' => $errors->first('address.permanentBarangay'),
                                        ])
                                    </x-slot:feedback>
                                </x-form.boxed-dropdown>
                            </div>
                        </div>

                        @include('components.form.input-feedback', [
                            'class' => 'd-none d-md-block',
                            'feedback_id' => 'permanent-applicant-address-feedback',
                            'message' =>
                                $errors->first('address.permanentRegion') ??
                                ($errors->first('address.permanentProvince') ??
                                    $errors->first('address.permanentCity')),
                        ])

                        <div class="row">
                            <div class="col">
                                <x-form.boxed-input-text id="permanent_address"
                                    label="{{ __('Permanent Home Address') }}" wire:model="address.permanentAddress"
                                    autocomplete="home street-address" :nonce="$nonce" :required="true"
                                    :readonly="when($this->samePresentAddressChckBox['checked'], true)"
                                    placeholder="{{ __('Room/Floor/Unit No. & Bldg. Name | House/Lot & Blk. No. | Street Name | Subdivision') }}"
                                    :showPlaceholderOnLabel="true">
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
