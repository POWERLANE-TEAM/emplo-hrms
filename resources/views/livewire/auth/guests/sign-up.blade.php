<section nonce="{{ $nonce }}" class="signUp-form ps-md-4 pe-md-5  pt-md-3 ">
    <hgroup class="d-flex flex-column text-center mb-3 mt-md-n4">
        <header class="display-6 fw-semibold text-primary mb-3">
            {{ __('register.sign_up.sign_up') }}
        </header>
        @if (!empty($job_vacancy))
            <span class="fs-5">
                {{ __('register.sign_up.for_application') }} <span class="fs-5 fw-semibold">
                    {{ strip_tags($job_vacancy['jobDetail']['jobTitle'][0]['job_title']) }}

                </span>
            </span>
        @endif
    </hgroup>

    <style>
        .modal.sign-up-form:has(form) section {
            max-height: 75vh;
            max-height: 75svh;
            overflow-y: auto;
            overflow-x: hidden;

            > {
                padding-right: ;
            }
        }
    </style>

    @livewire('auth.google-o-auth')

    <div class="my-3"></div>

    @livewire('auth.facebook-o-auth')

    <div class="my-5 d-flex align-items-center  border-bottom position-relative">
        <div class="position-absolute start-50 bg-body px-3 opacity-75 fw-medium text-uppercase translate-middle-x">or
        </div>
    </div>

    <form action="applicant/sign-up" nonce="{{ $nonce }}">
        @csrf

        <div class="d-flex gap-md-5">
            <div class="col-md-6">

                <x-form.input-text id="signUp-first-name" label="First Name" name="first_name"
                    pattern="/^[a-zA-Z\s]{1,255}$/" maxlength="191" autocomplete="given-name" :nonce="$nonce"
                    aria-owns="signUp-first-name-feedback"
                    class=" {{ $errors->has('first_name') ? 'is-invalid' : '' }}">

                    <x-slot:input_icon_left>
                        <i data-lucide="user-check-2"></i>
                    </x-slot:input_icon_left>

                    <x-slot:feedback>
                        @include('components.form.input-feedback', [
                            'feedback_id' => 'signUp-first-name-feedback',
                            'message' => $errors->first('first_name'),
                        ])
                    </x-slot:feedback>
                </x-form.input-text>

            </div>


            <div class="col-md-5">
                <x-form.input-text id="signUp-last-name" label="Last Name" name="last_name"
                    pattern="/^[a-zA-Z\s]{1,255}$/" maxlength="191" autocomplete="family-name" :nonce="$nonce"
                    aria-owns="signUp-last-name-feedback" class=" {{ $errors->has('last_name') ? 'is-invalid' : '' }}">

                    <x-slot:input_icon_left>
                        <i data-lucide="user"></i>
                    </x-slot:input_icon_left>

                    <x-slot:feedback>
                        @include('components.form.input-feedback', [
                            'feedback_id' => 'signUp-last-name-feedback',
                            'message' => $errors->first('last_name'),
                        ])
                    </x-slot:feedback>
                </x-form.input-text>
            </div>
        </div>


        <x-form.input-text id="signUp-middle-name" label="Middle Name" name="middle_name"
            pattern="/^[a-zA-Z\s]{1,255}$/" maxlength="191" autocomplete="additional-name" :nonce="$nonce"
            aria-owns="signUp-middle-name-feedback" class=" {{ $errors->has('middle_name') ? 'is-invalid' : '' }}">

            <x-slot:input_icon_left>
                <i data-lucide="user"></i>
            </x-slot:input_icon_left>

            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'signUp-middle-name-feedback',
                    'message' => $errors->first('middle_name'),
                ])
            </x-slot:feedback>
        </x-form.input-text>

        <x-form.email id="signUp-email" label="Email Address" name="email" autocomplete="email" :nonce="$nonce"
            class=" {{ $errors->has('email') ? 'is-invalid' : '' }}">
            <x-slot:input_icon_left>
                <i data-lucide="mail" class="icon-large"></i>
            </x-slot:input_icon_left>

            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'signUp-email-feedback',
                    'message' => $errors->first('email'),
                ])
            </x-slot:feedback>
        </x-form.email>

        <x-form.password id="signUp-password" label="Password" :has_confirm="true" name="password"
            autocomplete="new-password" :nonce="$nonce" class=" {{ $errors->has('password') ? 'is-invalid' : '' }}">

            <x-slot:input_icon_left>
                <i data-lucide="lock"></i>
            </x-slot:input_icon_left>

            <x-slot:toggle_password>
                @include('components.form.toggle-password', [
                    'toggler_id' => 'toggle-psw',
                    'controls' => 'signUp-password',
                ])
            </x-slot:toggle_password>

            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'signUp-password-feedback',
                    'message' => $errors->first('password'),
                ])
            </x-slot:feedback>
        </x-form.password>

        <x-form.password id="signUp-password-confirm" label="Confirm Password" name="password_confirmation"
            autocomplete="new-password" :nonce="$nonce" class=" {{ $errors->has('password') ? 'is-invalid' : '' }}">

            <x-slot:input_icon_left>
                <i data-lucide="shield-check"></i>
            </x-slot:input_icon_left>

            <x-slot:toggle_password>
                @include('components.form.toggle-password', [
                    'toggler_id' => 'toggle-psw-confirm',
                    'controls' => 'signUp-password-confirm',
                    'label' => 'Show/Hide Password Confirmation',
                ])
            </x-slot:toggle_password>

            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'signUp-password-confirm-feedback',
                    'message' => $errors->first('password'),
                ])
            </x-slot:feedback>
        </x-form.password>

        <x-form.checkbox-terms-condition :nonce="$nonce" />

        <div class="bottom-0 pt-1 pb-md-3 bg-body position-sticky z-3 w-100">
            <button type="submit" nonce="{{ $nonce }}" id="signUpBtn" class="btn btn-primary btn-lg w-100 ">
                Sign up
                <span class="spinner-border spinner-border-sm text-light" aria-hidden="true" wire:loading></span>
                <span class="visually-hidden" role="status" wire:loading>Processing...</span>
            </button>
        </div>
    </form>
</section>
