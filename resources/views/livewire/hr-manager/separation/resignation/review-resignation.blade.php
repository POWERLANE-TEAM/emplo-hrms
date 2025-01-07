<div>

    <!-- BACK-END REPLACE: Placeholder datas -->
    @php
        $determinedOn = '';
        // Date when HR processed and made a decision on the resignation (approval/rejection).
        // Set when HR approves or rejects the resignation.

        $status = 'pending';
        // Current status of the resignation letter submission.
        // Possible values: 'pending' (letter submitted but not yet reviewed),
        // 'approved' (resignation letter approved by HR), or 'rejected' (resignation letter rejected).

        $hasComments = false;
        // Indicates whether HR has left comments on the resignation letter.
        // Set to true if HR provided comments, false if no comments are given.
        // This should only appear if the resignation has been approved/rejected.


        // ===========================
        // Employee Status
        // ===========================
        $employeeStatus = 'regular';
        // The current employment status of the employee.
        // This is updated to 'resigned' if the resignation letter is approved.
    @endphp

    <!-- Dialogues -->

    <x-modals.dialog id="approveResignation">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Approve Resignation') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="mt-3">
                <x-form.boxed-textarea id="comment" label="Comments" :nonce="$nonce" :rows="6" :required="false"
                    placeholder="{{ __('Provide any feedback or additional instructions for the employee.') }}" />
            </div>
            <div class="fs-7">
                <p class="fs-7 fw-medium">
                    <b>Note</b>: Once approved, the resignation cannot be edited. Ensure all necessary discussions with
                    the employee have been completed.
                </p>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button class="btn btn-primary"
                onclick="switchModal('approveResignation', 'confirmApproval')">{{ __('Proceed with Approval') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <x-modals.dialog id="approveResignation">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Approve Resignation') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="mt-3">
                <x-form.boxed-textarea id="comment" label="Comments" :nonce="$nonce" :rows="6" :required="false"
                    placeholder="{{ __('Provide any feedback or additional instructions for the employee.') }}" />
            </div>
            <div class="fs-7">
                <p class="fs-7 fw-medium">
                    <b>Note</b>: Once approved, the resignation approval cannot be edited. Ensure all necessary
                    discussions with the employee have been completed.
                </p>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button class="btn btn-primary"
                onclick="switchModal('approveResignation', 'confirmApproval')">{{ __('Proceed with Approval') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <x-modals.dialog id="rejectResignation">
        <x-slot:title>
            <h1 class="modal-title fs-5" style="color:#dc3030">{{ __('Reject Resignation') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="mt-3">
                <x-form.boxed-textarea id="comment" label="Comments" :nonce="$nonce" :rows="6" :required="true"
                    placeholder="{{ __('State the reasons of the rejection.') }}" />
            </div>
            <div class="fs-7">
                <p class="fs-7 fw-medium">
                    <b>Note</b>: Please ensure that the reason for rejecting the resignation complies with Philippine
                    labor laws, specifically provisions under the Labor Code and relevant company policies. Rejection
                    should be based on valid grounds, such as ensuring a proper turnover process, pending obligations,
                    or other operational requirements.
                </p>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button class="btn btn-danger"
                onclick="switchModal('rejectResignation', 'confirmRejection')">{{ __('Proceed with Rejection') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <!-- Confirmational Modals -->
    <x-modals.confirmation.confirm-modal type="check" label="Confirm Approval" header="Approve Resignation"
        id="confirmApproval" message="This action cannot be undone. Are you sure you want to proceed?"
        actionButtonTitle="Confirm" wireAction="saveApproval" />

    <x-modals.confirmation.confirm-modal type="delete" label="Confirm Reject" header="Reject Resignation"
        id="confirmRejection" message="This action cannot be undone. Are you sure you want to proceed?"
        actionButtonTitle="Confirm" wireAction="saveRejection" />

    <section class="py-3">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex mx-0 px-0 mt-3 mt-md-n1" style="min-height: 50vh;">
                    <div class="flex-grow-1 border border-1 rounded-3 ">
                        <div class="flex-grow-1 px-4 position-relative">
                            <button type="button" aria-controls="iframe-resignation-letter"
                                class="text-dark shadow rounded-circle btn-full-screen"><i class="icon-medium"
                                    data-lucide="expand"></i></button>
                        </div>
                        <iframe id="iframe-resignation-letter" name="applicant-resume" class="rounded-3 "
                            allowfullscreen='yes' src="{{ Storage::url('hardware-and-software-components.pdf') }}"
                            height="100%" width="100%" frameborder="0" allowpaymentrequest="false"
                            loading="lazy"></iframe>
                        <!-- BACK-END REPLACE: PDF of the Resignation Letter -->
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="container">
                    <div class="px-lg-5 mb-4 flex-grow-1">
                        <header>
                            <div>
                                <p class="fw-bold fs-3 text-primary mb-0">Blackwell, Kelly Princess J.</p>
                                <p class="fs-5 fw-medium">Associate / Assistant Manager</p>
                            </div>

                            <!-- View Contact Information -->
                            <div>
                                <div>
                                    <p data-bs-toggle="collapse" data-bs-target="#contact-info" aria-expanded="false"
                                        aria-controls="contact-info" class="hover-opacity fs-7"
                                        style="cursor: pointer;">
                                        View Contact Information <i data-lucide="chevron-down"
                                            class="icon icon-large me-2"></i>
                                    </p>

                                    <div id="contact-info" class="collapse px-2">
                                        <p class="pt-3 pb-2"><i data-lucide="mail" class="icon icon-large me-2"></i>
                                            <b>Email:</b>
                                            <x-gmail-redirect email="blackwell.kpj@gmail.com" />
                                        </p>
                                        <p class="pb-2"><i data-lucide="phone" class="icon icon-large me-2"></i>
                                            <b>Contact
                                                Number:</b> +63 912 345 6789
                                        </p>
                                        <small>
                                            <a class="text-link-blue hover-opacity" href="#">Go to Profile</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <div class="mt-4">
                            <!-- Status -->
                            <p class="fw-bold mt-3 fs-5">Status:
                                @if ($status === 'pending')
                                    <span class="text-info">Pending</span>
                                @elseif ($status === 'approved')
                                    <span class="text-primary">Approved <i class="icon icon-large text-primary ms-1"
                                            data-lucide="badge-check"></i></span>
                                @elseif ($status === 'rejected')
                                    <span class="text-danger">Rejected <i class="icon icon-large text-danger ms-1"
                                            data-lucide="badge-x"></i></span>
                                @else
                                @endif
                            </p>

                            <!-- Submitted on -->
                            <p class="fw-bold mt-3 fs-5">Submitted on: <span class="fw-medium">January 20, 2024</span>
                            </p>

                            <!-- Determined on. If determinedOn date is not null -->
                            @if (!empty($determinedOn))
                                <p class="fw-bold mt-3 fs-5">Determined on:
                                    <span
                                        class="fw-medium">{{ \Carbon\Carbon::parse($determinedOn)->format('F j, Y') }}</span>
                                </p>
                            @endif

                            <!-- Comments -->
                            @if ($hasComments)
                                <div class="card border-primary mt-4 p-4 w-100">

                                    <div class="row">
                                        <div class="col">
                                            <p class="fw-bold fs-5">Comments</p>
                                        </div>

                                        <div class="col text-end">
                                            <button
                                                class="fs-7 underline fw-medium text-blue-info hover-opacity border-0 bg-transparent shadow-none p-0 m-0"
                                                onclick="openModal('approveLetter')">
                                                Edit
                                            </button>
                                        </div>
                                    </div>

                                    <p>Your resignation has been approved. Please check your email or contact HR for the
                                        next steps in the separation process. We appreciate your contributions and wish you
                                        the best in your future endeavors.</p>
                                </div>
                            @endif

                        </div>

                        @if ($status === 'pending')
                            <div class="mt-4">
                                <div class="d-flex align-items-center w-100">
                                    <button type="button" aria-controls="collapseControls"
                                        onclick="openModal('rejectResignation')" class="btn btn-danger me-2 w-25">
                                        {{ __('Reject') }}
                                    </button>
                                    <button type="button" class="btn btn-primary w-25"
                                        onclick="openModal('approveResignation')">
                                        {{ __('Approve') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    </section>
</div>

@script

<script>
Livewire.on('changes-saved', (event) => {
    console.log('Event object:', event);
    const modalId = event[0].modalId;
    
    if (modalId) {
        const modalEl = document.getElementById(modalId);
        if (modalEl) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.hide();
            console.log(`Modal with ID ${modalId} hidden successfully.`);
        } else {
            console.error(`Modal with ID ${modalId} not found!`);
        }
    } else {
        console.error('Modal ID not found in event data');
    }
});

</script>
@endscript