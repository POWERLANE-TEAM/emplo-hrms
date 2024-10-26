<div class="card">
    <div class="card-header">
        <h5>{{ __('Update Password') }}</h5>
        <p class="text-muted">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </div>
    <form wire:submit.prevent="updatePassword">
        <div class="card-body">
            <div class="mb-3">
                <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                <input type="password" id="current_password" class="form-control" wire:model="state.current_password" autocomplete="current-password">
                @error('current_password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('New Password') }}</label>
                <input type="password" id="password" class="form-control" wire:model="state.password" autocomplete="new-password">
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" id="password_confirmation" class="form-control" wire:model="state.password_confirmation" autocomplete="new-password">
                @error('password_confirmation')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end align-items-center">
            <span class="me-3 text-success" wire:loading.class="d-none" wire:target="updatePassword">{{ __('Saved.') }}</span>
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</div>
