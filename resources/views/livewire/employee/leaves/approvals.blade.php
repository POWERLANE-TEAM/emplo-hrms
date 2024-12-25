@props(['leave'])

<div class="card border-{{ $leave->denied_at ? 'danger' : 'primary' }} mt-1 px-5 py-4 w-100 h-100">
    <section>
        @if ($leave->denied_at)
            <div class="text-danger fs-3 fw-bold text-center">
                {{ __('Leave Request Denied') }}
            </div>

            <div class="ps-4 pe-2 py-3">
                <div class="d-flex flex-column">
                    <div class="fs-5">{{ $leave->deniedBy->full_name }}</div>
                    <div class="text-primary">{{ $leave->deniedBy->jobTitle->job_title }}</div>
                    <small class="text-muted">{{ $leave->denied_at }}</small>
                </div>
                <div class="mt-3">
                    <label for="reason" class="fw-semibold text-secondary-emphasis">{{ __('Reason for denial:') }}</label>
                    <textarea id="reason" class="form-control bg-body-secondary" rows="3" readonly>{{ $leave?->feedback }}</textarea>    
                </div>
            </div>
        @else
            <div class="text-primary fs-3 fw-bold text-center">
                {{ __('Approvals') }}
            </div>

            <div class="pb-3 pt-4">
                <p class="fw-medium fs-5">{{ __('Supervisor and Dept.Head/Manager') }}</p>

                <div class="ps-4 pe-2 py-3">
                    <x-form.checkbox container_class="" :nonce="$nonce" id="initialApproval" name="initial-approval"
                        class="checkbox checkbox-primary" disabled :checked="$leave->initial_approver_signed_at">

                        <x-slot:label>
                            <div class="d-flex flex-column">
                                <div class="fs-5">{{ $leave->initialApprover->full_name ?? __('Pending') }}</div>
                                <div class="text-primary">{{ $leave?->initialApprover?->jobTitle?->job_title }}</div>
                                <small class="text-muted">{{ $leave?->initial_approver_signed_at }}</small>
                            </div>
                        </x-slot:label>
                    </x-form.checkbox>
                </div>

                <div class="ps-4 pe-2 py-2">
                    <x-form.checkbox container_class="" :nonce="$nonce" id="secondApproval" name="second-approval"
                        class="checkbox checkbox-primary" disabled :checked="$leave->secondary_approver_signed_at">

                        <x-slot:label>
                            <div class="d-flex flex-column">
                                <div class="fs-5">{{ $leave->secondaryApprover->full_name ?? __('Pending') }}</div>
                                <div class="text-primary">{{ $leave?->secondaryApprover?->jobTitle?->job_title }}</div>
                                <small class="text-muted">{{ $leave?->secondary_approver_signed_at }}</small>
                            </div>
                        </x-slot:label>
                    </x-form.checkbox>
                </div>
            </div>

            <div class="pb-3">
                <p class="fw-medium fs-5">{{ __('Human Resources Department') }}</p>

                <div class="ps-4 pe-2 py-3">
                    <x-form.checkbox container_class="" :nonce="$nonce" id="thirdApproval" name="third-approval"
                        class="checkbox checkbox-primary" disabled :checked="$leave->third_approver_signed_at">

                        <x-slot:label>
                            <div class="d-flex flex-column">
                                <div class="fs-5">{{ $leave->thirdApprover->full_name ?? __('Pending') }}</div>
                                <div class="text-primary">{{ $leave?->thirdApprover?->jobTitle?->job_title }}</div>
                                <small class="text-muted">{{ $leave?->third_approver_signed_at }}</small>
                            </div>
                        </x-slot:label>
                    </x-form.checkbox>
                </div>

                <div class="ps-4 pe-2 py-2">
                    <div class="row">
                        <div class="col-7">
                            <x-form.checkbox container_class="" :nonce="$nonce" id="fourthApproval" name="fourth-approval"
                                class="checkbox checkbox-primary" disabled :checked="$leave->fourth_approver_signed_at">

                                <x-slot:label>
                                    <div class="d-flex flex-column">
                                        <div class="fs-5">{{ $leave->fourthApprover->full_name ?? __('Pending') }}</div>
                                        <div class="text-primary">{{ $leave?->fourthApprover?->jobTitle?->job_title }}</div>
                                        <small class="text-muted">{{ $leave?->fourth_approver_signed_at }}</small>
                                    </div>
                                </x-slot:label>
                            </x-form.checkbox>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>