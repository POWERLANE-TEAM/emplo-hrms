@php
    $nonce = csp_nonce();
@endphp




<section nonce="{{ $nonce }}" class=" px-md-4 py-md-3">
    <hgroup class="d-flex flex-column text-center mb-5 mt-md-n4">
        <header class="display-5 fw-semibold text-primary mb-3">
            Sign Up
        </header>
        @if (!empty($position))
            <span class="fs-5">
                Register to apply for <span class="fs-5 fw-semibold">
                    {{ $position->title }}

                </span>
            </span>
        @endif
    </hgroup>

    <form wire:submit.prevent="store" action="applicant/sign-up" nonce="{{ $nonce }}">
        @csrf
        <label for="signUp-email" class="mb-1">Email Address</label>
        <div class="input-group mb-3 position-relative mt-3">
            <div class="px-2 d-flex align-items-center position-absolute icon" wire:ignore><i data-lucide="mail"></i>
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
            <div class="px-2 d-flex position-absolute icon" wire:ignore nonce="{{ $nonce }}"><i
                    data-lucide="lock"></i></div>
            <input type="password" id="signUp-password" aria-owns="signUp-password-feedback signUp-password-confirm"
                name="password" pattern="/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[^\s]{8,72}$/" minlength="8"
                maxlength="72" required wire:model="password" autocomplete="new-password"
                class="form-control rm-bg-icon border-bottom ps-5 z-0
                @error('password')
                    is-invalid
                @enderror

                ">
            <input type="checkbox" id="toggle-psw" class="text-primary toggle-password position-absolute mt-2 end-0 z-3"
                aria-label="Show/Hide Password" aria-keyshortcuts="alt+f8">
            <div class="invalid-feedback" role="alert" id="signUp-password-feedback">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <label for="signUp-password-confirm" class="mb-1">Confirm Password</label>
        <div class="input-group mb-3">
            <div class="px-2 d-flex position-absolute icon " wire:ignore nonce="{{ $nonce }}"><i
                    data-lucide="lock"></i></div>
            <input type="password" id="signUp-password-confirm" aria-owns="signUp-password-confirm-feedback"
                name="password_confirmation" minlength="8" maxlength="72" required wire:model="password_confirmation"
                autocomplete="off"
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
            <label for="terms-condition" class="checkbox-label d-flex flex-wrap">I agree to
                the&#8194;<wbr>
                <span class="d-flex" role="list">
                    <a href="#" target="_blank" class="text-black" rel="noopener noreferrer"
                        role="listitem">Terms&nbsp;&&nbsp;Conditions
                    </a>
                    <span>&#8194;and&#8194;</span>
                    <a href="#" target="_blank" class="text-black"
                        rel="noopener noreferrer">Privacy&nbsp;Policy</a>
                </span>
            </label>
            <div class="invalid-feedback"></div>
        </div>

        <button type="submit" nonce="{{ $nonce }}" id="signUpBtn" class="btn btn-primary btn-lg w-100"
            disabled>Sign
            Up</button>

    </form>
</section>
