<section class="my-5">
    <div class="card">
        <div class="card-header">
            <div class="fs-5 fw-medium">{{ __('Browser Sessions') }}</div>
            <p class="small text-muted mb-0">{{ __('Manage and log out your active sessions on other browsers and devices.') }}</p>
        </div>
        <div class="card-body">
            <p class="text-muted">{{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}</p>
    
            @if (count($this->sessions) > 0)
                <div class="mt-4">

                    {{-- Other Browser Sessions --}}
                    @foreach ($this->sessions as $session)
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                @if ($session->agent->isDesktop())
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-display" viewBox="0 0 16 16">
                                    <path d="M0 4s0-2 2-2h12s2 0 2 2v6s0 2-2 2h-4q0 1 .25 1.5H11a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1h.75Q6 13 6 12H2s-2 0-2-2zm1.398-.855a.76.76 0 0 0-.254.302A1.5 1.5 0 0 0 1 4.01V10c0 .325.078.502.145.602q.105.156.302.254a1.5 1.5 0 0 0 .538.143L2.01 11H14c.325 0 .502-.078.602-.145a.76.76 0 0 0 .254-.302 1.5 1.5 0 0 0 .143-.538L15 9.99V4c0-.325-.078-.502-.145-.602a.76.76 0 0 0-.302-.254A1.5 1.5 0 0 0 13.99 3H2c-.325 0-.502.078-.602.145"/>
                                    </svg>
                                @else
                                    {{-- svg icons --}}
                                @endif
                            </div>
    
                            <div class="ms-3">
                                <p class="mb-0 small text-muted">{{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}</p>
                                <p class="small text-muted">
                                    {{ $session->ip_address }},
                                    @if ($session->is_current_device)
                                        <span class="text-success fw-semibold">{{ __('This device') }}</span>
                                    @else
                                        {{ __('Last active') }} {{ $session->last_active }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
    
            <div class="d-flex justify-content-start align-items-center">
                <button type="button" class="btn btn-primary py-2" wire:click="confirmLogout" wire:loading.attr="disabled">
                    {{ __('Log Out Other Browser Sessions') }}
                </button>
                <span class="ms-3 text-success"
                    x-data="{ shown: false, timeout:null }"
                    x-init="@this.on('loggedOut', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
                    x-show.transition.out.opacity.duration.1500ms="shown"
                    x-transition:leave.opacity.duration.1500ms
                    style="display: none" 
                    wire:target="loggedOut" 
                    wire:loading.class="d-none"
                    >{{ __('Done.') }}
            </span>
            </div>
        </div>
    </div>
    
    {{-- Log Out Other Devices Confirmation Modal --}}
    <div class="modal {{ $this->confirmingLogout ? 'd-block' : 'fade' }}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250) tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="{{ ! $this->confirmingLogout }}">
        <div class="modal-dialog modal-lg" wire:model.live="confirmingLogout">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">{{ __('Log Out Other Browser Sessions') }}</h5>
                </div>
                <div class="modal-body">
                    <p>{{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}</p>
                    <div class="mt-3">
                        <input type="password" class="form-control mt-1"
                            id="password" 
                            placeholder="{{ __('Password') }}"
                            wire:model="password" 
                            wire:keydown.enter="logoutOtherBrowserSessions" />
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">{{ __('Cancel') }}
                    </button>

                    <button type="button" class="btn btn-primary ms-2" 
                            wire:click="logoutOtherBrowserSessions" 
                            wire:loading.attr="disabled">
                        {{ __('Log Out Other Browser Sessions') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</section>