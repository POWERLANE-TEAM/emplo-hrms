@php
    $nonce = csp_nonce();
@endphp

<section nonce="{{ $nonce }}" class=" ps-md-4 pe-md-5  py-md-3 ">
    <hgroup class="d-flex flex-column text-center mb-5 mt-md-n4">
        <header class="display-5 fw-semibold text-primary mb-3">
            {{ __('register.sign_up.sign_up') }}
        </header>
        @if (!empty($job_vacancy))
            <span class="fs-5">
                {{ __('register.sign_up.for_application') }} <span class="fs-5 fw-semibold">
                    {{ $job_vacancy['job_details']['job_title']['job_title'] }}

                </span>
            </span>
        @endif
    </hgroup>

    <style>
        .gsi-material-button {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            -webkit-appearance: none;
            background-color: WHITE;
            background-image: none;
            border: 1px solid #747775;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: #1f1f1f;
            cursor: pointer;
            font-family: 'Roboto', arial, sans-serif;
            font-size: 14px;
            height: 40px;
            letter-spacing: 0.25px;
            outline: none;
            overflow: hidden;
            padding: 0 12px;
            position: relative;
            text-align: center;
            -webkit-transition: background-color .218s, border-color .218s, box-shadow .218s;
            transition: background-color .218s, border-color .218s, box-shadow .218s;
            vertical-align: middle;
            white-space: nowrap;
            width: auto;
            max-width: 400px;
            min-width: min-content;
        }

        .gsi-material-button .gsi-material-button-icon {
            height: 20px;
            margin-right: 12px;
            min-width: 20px;
            width: 20px;
        }

        .gsi-material-button .gsi-material-button-content-wrapper {
            -webkit-align-items: center;
            align-items: center;
            display: flex;
            -webkit-flex-direction: row;
            flex-direction: row;
            -webkit-flex-wrap: nowrap;
            flex-wrap: nowrap;
            height: 100%;
            justify-content: center;
            position: relative;
            width: 100%;
        }

        .gsi-material-button .gsi-material-button-contents {
            -webkit-flex-grow: 0;
            flex-grow: 0;
            font-family: 'Roboto', arial, sans-serif;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: top;
        }

        .gsi-material-button .gsi-material-button-state {
            -webkit-transition: opacity .218s;
            transition: opacity .218s;
            bottom: 0;
            left: 0;
            opacity: 0;
            position: absolute;
            right: 0;
            top: 0;
        }

        .gsi-material-button:disabled {
            cursor: default;
            background-color: #ffffff61;
            border-color: #1f1f1f1f;
        }

        .gsi-material-button:disabled .gsi-material-button-contents {
            opacity: 38%;
        }

        .gsi-material-button:disabled .gsi-material-button-icon {
            opacity: 38%;
        }

        .gsi-material-button:not(:disabled):active .gsi-material-button-state,
        .gsi-material-button:not(:disabled):focus .gsi-material-button-state {
            background-color: #303030;
            opacity: 12%;
        }

        .gsi-material-button:not(:disabled):hover {
            -webkit-box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 1px 3px 1px rgba(60, 64, 67, .15);
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 1px 3px 1px rgba(60, 64, 67, .15);
        }

        .gsi-material-button:not(:disabled):hover .gsi-material-button-state {
            background-color: #303030;
            opacity: 8%;
        }

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

    <div class="row justify-content-center align-items-center ">
        <a href="auth/google/redirect"
            class="gsi-material-button btn btn-lg w-100 link-offset-2 link-underline link-underline-opacity-0"
            style="width:400px" role="button">
            <div class="gsi-material-button-state"></div>
            <div class="gsi-material-button-content-wrapper">
                <div class="gsi-material-button-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                        xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                        <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                        </path>
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                        </path>
                        <path fill="#FBBC05"
                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                        </path>
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                        </path>
                        <path fill="none" d="M0 0h48v48H0z"></path>
                    </svg>
                </div>
                <span class="gsi-material-button-contents">Continue with Google</span>
                <span style="display: none;">Continue with Google</span>
            </div>
        </a>
    </div>

    <div class="my-5 d-flex align-items-center  border-bottom position-relative">
        <div class="position-absolute start-50 bg-body px-3 opacity-75 fw-medium text-uppercase translate-middle-x">or
        </div>
    </div>

    <form wire:submit.prevent="store" action="applicant/sign-up" nonce="{{ $nonce }}">
        @csrf

        <div class="d-flex gap-md-5">
            <div class="col-md-6">
                <label for="signUp-first-name" class="mb-1">First Name</label>
                <div class="input-group mb-3 position-relative">
                    <div class="px-2 d-flex align-items-center position-absolute text-primary icon " wire:ignore><i
                            data-lucide="user-check-2"></i>
                    </div>
                    <input type="text" aria-owns="signUp-first-name-feedback" name="first_name"
                        pattern="/^[a-zA-Z\s]{1,255}$/" maxlength="255" required wire:model="first_name"
                        autocomplete="given-name"
                        class="form-control border-bottom ps-5
                @error('first_name')
                    is-invalid
                @enderror
                ">
                    <div class="invalid-feedback" role="alert" id="signUp-first-name-feedback">
                        @error('first_name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <label for="signUp-last-name" class="mb-1">Last Name</label>
                <div class="input-group mb-3 position-relative">
                    <div class="px-2 d-flex align-items-center position-absolute text-primary icon" wire:ignore><i
                            data-lucide="user"></i>
                    </div>
                    <input type="text" aria-owns="signUp-last-name-feedback" name="last_name"
                        pattern="/^[a-zA-Z\s]{1,255}$/" maxlength="255" required wire:model="last_name"
                        autocomplete="family-name"
                        class="form-control border-bottom ps-5
                @error('last_name')
                    is-invalid
                @enderror
                ">
                    <div class="invalid-feedback" role="alert" id="signUp-last-name-feedback">
                        @error('last_name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>


        <label for="signUp-middle-name" class="mb-1">Middle Name</label>
        <div class="input-group mb-3 position-relative">
            <div class="px-2 d-flex align-items-center position-absolute text-primary icon" wire:ignore><i
                    data-lucide="user"></i>
            </div>
            <input type="text" aria-owns="signUp-middle-name-feedback" name="middle_name"
                pattern="/^[a-zA-Z\s]{1,255}$/" maxlength="255" wire:model="middle_name" autocomplete="given-name"
                class="form-control border-bottom ps-5
            @error('middle_name')
                is-invalid
            @enderror
            ">
            <div class="invalid-feedback" role="alert" id="signUp-middle-name-feedback">
                @error('middle_name')
                    {{ $message }}
                @enderror
            </div>
        </div>



        <label for="signUp-email" class="mb-1">Email Address</label>
        <div class="input-group mb-3 position-relative mt-3">
            <div class="px-2 d-flex align-items-center position-absolute text-primary icon" wire:ignore><i
                    data-lucide="mail"></i>
            </div>
            <input type="email" aria-owns="signUp-email-feedback" name="email"
                pattern="/^[a-zA-Z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,4}$/" maxlength="255" required wire:model="email"
                autocomplete="email"
                class="form-control  border-bottom ps-5
                @error('email')
                    is-invalid
                @enderror
                ">
            <div class="invalid-feedback" role="alert" id="signUp-email-feedback">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <label for="signUp-password" class="mb-1">Password</label>
        <div class="input-group mb-3">
            <div class="px-2 d-flex position-absolute text-primary icon" wire:ignore nonce="{{ $nonce }}"><i
                    data-lucide="lock"></i></div>
            <input type="password" id="signUp-password" aria-owns="signUp-password-feedback signUp-password-confirm"
                name="password" pattern="/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[^\s]{8,72}$/" minlength="8"
                maxlength="72" required wire:model="password" autocomplete="new-password"
                class="form-control rm-bg-icon border-bottom ps-5 z-0
                @error('password')
                    is-invalid
                @enderror

                ">
            <input type="checkbox" id="toggle-psw"
                class="text-primary toggle-password position-absolute mt-2 end-0 z-3" aria-label="Show/Hide Password"
                aria-keyshortcuts="alt+f8">
            <div class="invalid-feedback" role="alert" id="signUp-password-feedback">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <label for="signUp-password-confirm" class="mb-1">Confirm Password</label>
        <div class="input-group mb-3">
            <div class="px-2 d-flex position-absolute text-primary icon " wire:ignore nonce="{{ $nonce }}"><i
                    data-lucide="shield-check"></i></div>
            <input type="password" id="signUp-password-confirm" aria-owns="signUp-password-confirm-feedback"
                name="password_confirmation" minlength="8" maxlength="72" required
                wire:model="password_confirmation" autocomplete="new-password"
                class="form-control rm-bg-icon border-bottom ps-5 z-0
                                @error('password')
                    is-invalid
                @enderror
                ">
            <input type="checkbox" id="toggle-psw-confirm"
                class="text-primary toggle-password position-absolute mt-2 end-0 z-3"
                aria-label="Show/Hide Password Confirmation" aria-keyshortcuts="alt+f8">
            <div class="invalid-feedback" role="alert" id="signUp-password-confirm-feedback">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="input-group mb-3 terms-condition">
            <input type="checkbox" id="terms-condition" name="consent" required wire:model="consent"
                class="checkbox checkbox-primary">
            <label for="terms-condition" class="checkbox-label d-flex flex-wrap"> {!! trans('consent.consent') !!}
                &#8194;<wbr>
                <span class="d-flex" role="list">
                    <a href="#" target="_blank" class="text-black" rel="noopener noreferrer" role="listitem">
                        {!! __('consent.term_condition') !!}
                    </a>
                    <span>&#8194;{{ __('common.and') }}&#8194;</span>
                    <a href="#" target="_blank" class="text-black"
                        rel="noopener noreferrer">{!! __('consent.privacy_policy') !!}</a>
                </span>
            </label>
            <div class="invalid-feedback"></div>
        </div>

        <button type="submit" nonce="{{ $nonce }}" id="signUpBtn" class="btn btn-primary btn-lg w-100"
            disabled>{{ __('register.sign_up.sign_up') }}
            <span class="spinner-border spinner-border-sm text-light" aria-hidden="true" wire:loading></span>
            <span class="visually-hidden" role="status" wire:loading>Processing...</span>
        </button>


    </form>
</section>
