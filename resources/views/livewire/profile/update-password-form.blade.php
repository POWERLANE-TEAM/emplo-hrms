<section class="my-5">
    <div class="card pb-3">
        <div class="card-header">
            <div class="fs-5 fw-medium">{{ __('Update Password') }}</div>
            <p class="small text-muted mb-0">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        </div>
        <form wire:submit.prevent="updatePassword">
            <div class="card-body">
                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input type="password" id="current_password" class="form-control shadow-sm" wire:model="state.current_password" autocomplete="current-password">
                    @error('current_password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <input type="password" id="password" class="form-control shadow-sm" wire:model="state.password" autocomplete="new-password">
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input type="password" id="password_confirmation" class="form-control shadow-sm" wire:model="state.password_confirmation" autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center">
                <span class="me-3 text-success"
                    x-data="{ shown: false, timeout:null }"
                    x-init="@this.on('saved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
                    x-show.transition.out.opacity.duration.1500ms="shown"
                    x-transition:leave.opacity.duration.1500ms
                    style = "display: none;"
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