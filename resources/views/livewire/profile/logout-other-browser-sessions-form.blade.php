<div class="row my-5">

    <div class="col-md-4">

        <section>
            <p class="fs-3 fw-bold">
                Browser Sessions
            </p>

            <p>
                Manage and log your active sessions on other browsers and devices.
            </p>
        </section>
    </div>

    <div class="col-md-8">
        <section wire:poll.visible>
            <div class="card p-4 pb-3 border-0 bg-secondary-subtle">
                <p>
                    {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
                </p>

                <h5 class="mt-2 mb-0 letter-spacing-2 text-uppercase text-primary fw-semibold">Sessions</h5>

                @if (count($this->sessions) > 0)
                    <div class="mt-2">

                        {{-- Other Browser Sessions --}}
                        @foreach ($this->sessions as $session)
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    @if ($session->agent->isDesktop())
                                        <i data-lucide="monitor" class="icon icon-xlarge"></i>
                                    @else
                                        <i data-lucide="smartphone" class="icon icon-xlarge"></i>
                                    @endif
                                </div>

                                <div class="d-flex flex-column text-start ms-3">
                                    <p class="mb-0 small">
                                        {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} -
                                        {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                                    </p>
                                    <p class="mb-0 small">
                                        {{ $session->ip_address }},
                                        @if ($session->is_current_device)
                                            <span class="text-success">{{ __('This device') }}</span>
                                        @else
                                            {{ __('Last active') }} {{ $session->last_active }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-3 d-flex justify-content-start align-items-center">
                    <button type="button" class="btn btn-primary py-2" wire:click="confirmLogout"
                        wire:loading.attr="disabled">
                        {{ __('Log Out Other Browser Sessions') }}
                    </button>
                    <span class="ms-3 text-success" x-data="{ shown: false, timeout:null }"
                        x-init="@this.on('loggedOut', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
                        x-show.transition.out.opacity.duration.1500ms="shown" x-transition:leave.opacity.duration.1500ms
                        style="display: none" wire:target="loggedOut" wire:loading.class="d-none">{{ __('Done.') }}
                    </span>
                </div>
            </div>

            {{-- Log Out Other Devices Confirmation Modal --}}
            <div class="modal {{ $this->confirmingLogout ? 'd-block' : 'fade' }}"
                x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250) tabindex="
                -1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="{{ !$this->confirmingLogout }}">
                <div class="modal-dialog modal-lg" wire:model.live="confirmingLogout">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmLogoutModalLabel">
                                {{ __('Log Out Other Browser Sessions') }}
                            </h5>
                        </div>
                        <div class="modal-body">
                            <p>{{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}
                            </p>
                            <div class="mt-3">
                                <input type="password" class="form-control mt-1" id="password"
                                    placeholder="{{ __('Password') }}" wire:model="password"
                                    wire:keydown.enter="logoutOtherBrowserSessions" />
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingLogout')"
                                wire:loading.attr="disabled">{{ __('Cancel') }}
                            </button>

                            <button type="button" class="btn btn-primary ms-2" wire:click="logoutOtherBrowserSessions"
                                wire:loading.attr="disabled">
                                {{ __('Log Out Other Browser Sessions') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

@script
<script>
    lucide.createIcons();

    // Recreate icons after Livewire updates
    Livewire.hook('morph.added', () => {
        lucide.createIcons();
    });
</script>
@endscript