@use ('Illuminate\View\ComponentAttributeBag')

<div>
    @include('livewire.applicant.application.application-wizard-progress-bar')
    <div class="d-contents">
        <section class="container " aria-label="Application Second Step ">

            <div class="my-3 col-md-8 mx-auto">


                <div>

                    @php
                        $accepted = [
                            'image' => ['png', 'jpeg', 'webp'],
                        ];
                    @endphp

                    @if (!is_null($displayProfileUrl) && filter_var($displayProfileUrl, FILTER_VALIDATE_URL))
                        <div>
                            <div class="rounded-circle border mx-auto">
                                <img class="form-display-profile rounded-circle" src="{{ $displayProfileUrl }}"
                                    alt="">
                            </div>
                        </div>
                    @endif

                    @php
                        $allowImagePreview =
                            !is_null($displayProfileUrl) && filter_var($displayProfileUrl, FILTER_VALIDATE_URL)
                                ? 'false'
                                : 'true';
                    @endphp

                    @include('components.form.input-feedback', [
                        'feedback_id' => 'sample-profile-feedback',
                        'message' => $errors->first('displayProfile'),
                    ])

                    @php
                        $profileUploadLabel = <<<HTML
                        Drag & Drop or <span class="filepond--label-action"> Browse</span> a Profile Picture
                        HTML;
                    @endphp

                    {{-- Hide Preview for now --}}
                    <div
                        class="{{ !is_null($displayProfileUrl) && filter_var($displayProfileUrl, FILTER_VALIDATE_URL) ? 'd-none' : '' }}">
                        {{-- Image size client validation not working --}}
                        <x-filepond::upload id="display-profile" label-idle="{!! $profileUploadLabel !!}"
                            image-validate-size-min-width="160" image-validate-size-min-height="160"
                            image-validate-size-max-width="2160" image-validate-size-max-height="2160"
                            allow-image-validate-size="true" image-validate-size-max-resolution="2160"
                            wire:model="displayProfile" :accept="$accepted" allow-image-preview="{{ $allowImagePreview }}"
                            instant-upload="false" :required="true" />
                        @include('components.form.input-feedback', [
                            'feedback_id' => 'display-profile-feedback',
                            'message' => $errors->first('displayProfile'),
                        ])
                    </div>

                    <datalist id="applicant-names" wire:model="parsedNameSegment">
                        @if (!empty($this->parsedNameSegment))
                            @foreach ($this->parsedNameSegment as $namePart)
                                <option value="{{ $namePart }}">{{ $namePart }}</option>
                            @endforeach
                        @endif
                    </datalist>

                    <datalist id="applicant-email-list">
                        @if (optional(auth()->user()->email))
                            <option value="{{ auth()->user()->email }}"></option>
                        @endif
                        @if (!empty($this->parsedResume['employee_email']))
                            @if (is_array($this->parsedResume['employee_email']))
                                @foreach ($this->parsedResume['employee_email'] as $contact)
                                    <option value="{{ $email }}">{{ $email }}</option>
                                @endforeach
                            @else
                                <option value="{{ $this->parsedResume['employee_email'] }}">
                                    {{ $this->parsedResume['employee_email'] }}</option>
                            @endif
                        @endif
                    </datalist>

                    <datalist id="applicant-contact-list">
                        @if (!empty($this->parsedResume['employee_contact']))
                            @if (is_array($this->parsedResume['employee_contact']))
                                @foreach ($this->parsedResume['employee_contact'] as $contact)
                                    <option value="{{ $contact }}">{{ $contact }}</option>
                                @endforeach
                            @else
                                <option value="{{ $this->parsedResume['employee_contact'] }}">
                                    {{ $this->parsedResume['employee_contact'] }}</option>
                            @endif
                        @endif
                    </datalist>

                    <x-form.boxed-input-text id="applicant-last-name" label="{{ __('Last Name') }}" type="list"
                        list="applicant-names" name="applicant.name.lastName" autocomplete="family-name"
                        class=" {{ $errors->has('applicant.name.lastName') ? 'is-invalid' : '' }}" :nonce="$nonce"
                        required placeholder="Smith">

                        <x-slot:feedback>
                            @include('components.form.input-feedback', [
                                'feedback_id' => 'applicant-last-name-feedback',
                                'message' => $errors->first('applicant.name.lastName'),
                            ])
                        </x-slot:feedback>

                    </x-form.boxed-input-text>

                    <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
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

                    </div>

                    <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
                        <div class="flex-1">
                            <x-form.boxed-dropdown id="sex-at-birth" label="{{ __('Sex at birth') }}" name="sexAtBirth"
                                class=" {{ $errors->has('sexAtBirth') ? 'is-invalid' : '' }}" :nonce="$nonce"
                                required :options="$this->sexes" placeholder="Select type">

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'sex-at-birth-feedback',
                                        'message' => $errors->first('sexAtBirth'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-dropdown>
                        </div>

                        @php
                            $ageRule = new App\Rules\WorkAgeRule();
                        @endphp
                        <div class="flex-1">
                            <x-form.boxed-date label="Birthdate" id="aaplicant-birth-date"
                                max="{{ $ageRule->getMaxDate() }}" min="{{ $ageRule->getMinDate() }}"
                                class=" {{ $errors->has('applicant.birth') ? 'is-invalid' : '' }}"
                                name="applicant.birth" placeholder="mm/dd/yyy" :nonce="$nonce" required>

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-birth-date-feedback',
                                        'message' => $errors->first('applicant.birth'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-date>
                        </div>
                    </div>

                    <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
                        <div class="flex-1">
                            <x-form.boxed-email id="applicant-email" label="Email Address" name="applicant.email"
                                list="applicant-email-list" autocomplete="email" :nonce="$nonce"
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
                    </div>

                </div>

            </div>

        </section>
        @include('livewire.applicant.application.application-wizard-nav-btn')
    </div>
</div>
