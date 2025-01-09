<div class="row my-5">
    <div class="col-md-4">
        <section>
            <p class="fs-3 fw-bold">
                Update Password
            </p>

            <p>
                Boost your account security by choosing a strong password.
                <a class="text-link-blue hover-opacity" href="#">Find out more about how to create a strong
                    password.
                </a>
            </p>
        </section>
    </div>

    <div class="col-md-8">
        <section>
            <div class="card p-4 pb-3 border-0 bg-secondary-subtle">
                <form wire:submit.prevent="updatePassword">
                    <div>
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">{{ __('Current Password') }}<span class="text-danger">*</span></label>
                            <input type="password" id="current_password" class="form-control"
                                wire:model="state.current_password" autocomplete="current-password" placeholder="Enter your current password...">
                            @error('current_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">{{ __('New Password') }}<span class="text-danger">*</span></label>
                            <input type="password" id="password" class="form-control"
                                wire:model="state.password" autocomplete="new-password" placeholder="Enter your new password...">
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">{{ __('Confirm Password') }}<span class="text-danger">*</span></label>
                            <input type="password" id="password_confirmation" class="form-control"
                                wire:model="state.password_confirmation" autocomplete="new-password" placeholder="Re-enter your new password...">
                            @error('password_confirmation')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <span class="me-3 text-success" x-data="{ shown: false, timeout:null }"
                            x-init="@this.on('saved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
                            x-show.transition.out.opacity.duration.1500ms="shown"
                            x-transition:leave.opacity.duration.1500ms style="display: none;"
                            wire:loading.class="d-none"
                            wire:target="updatePassword">{{ __('Password changed successfully.') }}
                        </span>
                        <button type="submit" class="btn btn-primary me-3 py-2">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>