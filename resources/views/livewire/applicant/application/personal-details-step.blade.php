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

                    <div>
                        <x-filepond::upload wire:model="form.displayProfile" instant-upload="false" {{-- name="resume.file" --}}
                            allow-image-edit="true" {{-- image-edit-allow-edit="true" --}} image-edit-instant-edit :accept="$accepted"
                            :required="true" />
                        @include('components.form.input-feedback', [
                            'feedback_id' => 'displayProfile',
                            'message' => $errors->first('displayProfile.file'),
                        ])
                    </div>

                    <datalist id="applicant-names" wire:model="parsedNameSegment">
                        @if (!empty($this->parsedNameSegment))
                            @foreach ($this->parsedNameSegment as $namePart)
                                <option value="{{ $namePart }}">{{ $namePart }}</option>
                            @endforeach
                        @endif
                    </datalist>

                    <x-form.boxed-input-text id="applicant-last-name" label="{{ __('Last Name') }}" type="list"
                        list="applicant-names" name="form.applicantName.lastName"
                        class=" {{ $errors->has('applicantName.lastName') ? 'is-invalid' : '' }}" :nonce="$nonce"
                        required placeholder="Smith">

                        <x-slot:feedback>
                            @include('components.form.input-feedback', [
                                'feedback_id' => 'applicant-last-name',
                                'message' => $errors->first('applicantName.lastName'),
                            ])
                        </x-slot:feedback>

                    </x-form.boxed-input-text>

                    <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
                        <div class="flex-1-sm-grow">
                            <x-form.boxed-input-text id="applicant-first-name" label="{{ __('First Name') }}"
                                name="form.applicantName.firstName" type="list" list="applicant-names"
                                class=" {{ $errors->has('applicantName.firstName') ? 'is-invalid' : '' }}"
                                :nonce="$nonce" placeholder="Johny" required>

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-first-name',
                                        'message' => $errors->first('applicantName.firstName'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-input-text>
                        </div>

                        <div class="flex-1-sm-grow">
                            <x-form.boxed-input-text id="applicant-middle-name" label="{{ __('Middle Name') }}"
                                name="form.applicantName.middleName" type="list" list="applicant-names"
                                class=" {{ $errors->has('applicantName.middleName') ? 'is-invalid' : '' }}"
                                :nonce="$nonce" placeholder="Doe">

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-middle-name',
                                        'message' => $errors->first('applicantName.middleName'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-input-text>
                        </div>

                    </div>

                    <div class="input-group column-gap-3 column-gap-md-4  flex-wrap flex-md-nowrap">
                        <div class="flex-1">
                            <x-form.boxed-dropdown id="sex-at-birth" label="{{ __('Sex at birth') }}" name="sexAtBirth"
                                class=" {{ $errors->has('sexAtBirth') ? 'is-invalid' : '' }}" :nonce="$nonce" required
                                :options="$this->sexes" placeholder="Select type">

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'sex-at-birth',
                                        'message' => $errors->first('sexAtBirth'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-dropdown>
                        </div>
                        <div class="flex-1">
                            <x-form.boxed-date label="Birthdate" id="aaplicant-birth-date"
                                class=" {{ $errors->has('applicantBirth') ? 'is-invalid' : '' }}"
                                name="form.applicantBirth" placeholder="mm/dd/yyy" :nonce="$nonce" required>

                                <x-slot:feedback>
                                    @include('components.form.input-feedback', [
                                        'feedback_id' => 'applicant-birth-date',
                                        'message' => $errors->first('applicantBirth.date'),
                                    ])
                                </x-slot:feedback>
                            </x-form.boxed-date>
                        </div>
                    </div>


                </div>

            </div>


        </section>
        @include('livewire.applicant.application.application-wizard-nav-btn')
    </div>
</div>
