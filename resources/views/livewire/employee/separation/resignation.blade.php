<div>

    <!-- BACK-END REPLACE: Replace placeholder datas. -->

    @php
        // ===========================
        // Resignation Letter State
        // ===========================
        $isEmpty = true;
        // Boolean flag indicating if the resignation letter is submitted.
        // If true, it triggers the empty state (no letter submitted).
        // Toggle this flag if a resignation letter is submitted.

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


        // ===========================
        // Employee Status
        // ===========================
        $employeeStatus = 'regular';
        // The current employment status of the employee.
        // This is updated to 'resigned' if the resignation letter is approved.


        // ===========================
        // COE Request
        // ===========================
        $requestCertificate = false;
        // Boolean flag to track if the employee has requested a Certificate of Employment (COE).
        // Set to true if the employee requests for a COE after resignation approval.

        $requestCertificateStatus = '';
        // Status of the COE request.
        // Possible values: 'pending' (request made but not yet processed),
        // 'issued' (COE has been issued to the employee).
    @endphp


    <!-- Empty State -->
    @if ($isEmpty)
        <section class="py-3">
            <div class="container">
                <!-- Row for the Image -->
                <div class="row justify-content-center mb-4">
                    <div class="col-12 text-center">
                        <img class="img-size-20 img-responsive"
                            src="{{ Vite::asset('resources/images/illus/empty-states/files-and-folder.webp') }}" alt="">
                    </div>
                </div>

                <!-- Row for the Text -->
                <div class="row justify-content-center mb-4">
                    <div class="col-12 text-center">
                        <p class="fs-3 fw-bold mb-0 pb-1">You haven’t submitted a resignation yet.</p>
                        <p class="fs-6 fw-medium">If you’re considering ending your employment, you can submit your
                            resignation letter here.</p>
                    </div>
                </div>

                <!-- Row for Buttons -->
                <div class="row justify-content-center">
                    <div class="col-12 text-center mb-3">
                        <a href="file-resignation"
                            class="btn btn-primary btn-lg w-25 d-flex align-items-center justify-content-center mx-auto">
                            <i data-lucide="file-plus-2" class="icon icon-large me-2"></i>
                            File Resignation Letter
                        </a>
                    </div>

                    <!-- REPLACE STATIC PAGE LINK: separation Process Policy -->
                    <div class="col-12 text-center">
                        <a href="#" id="toggle-information" class="text-link-blue text-decoration-underline fs-7">
                            Learn more about the separation process
                        </a>
                    </div>
                </div>
            </div>
        </section>

    @endif

    <!-- Presence of Resignation Letter -->
    @if (!$isEmpty)

        <!-- BACK-END REPLACE NOTE: This notice should be always at every page after the employee has been
            resigned. It can be moved to the layout. -->

        @if ($employeeStatus === 'resigned')
            @include('components.includes.callouts.data-retention-notice')
        @endif

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
                                <h3 class="text-primary fw-bold">Resignation Letter</h3>
                            </header>

                            <div>

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

                                <!-- SECTION: Status Pending -->
                                @if ($status === 'pending')

                                    <x-info_panels.note
                                        note="{{ __('You can cancel your resignation as long as it is still pending approval. Once processed, cancellation is no longer possible.') }}" />

                                    <button type="submit" id="applicant-decline-resume" name="submit"
                                        class="btn btn-lg btn-danger w-25">Retract</button>

                                    <!-- SECTION: Status Approved / Rejected -->
                                @else
                                    <!-- Comments -->
                                    @if ($hasComments)
                                        <div class="card border-primary mt-4 p-4 w-100">
                                            <p class="fw-bold fs-5">Comments</p>
                                            <p>Your resignation has been approved. Please check your email or contact HR for the
                                                next steps in the separation process. We appreciate your contributions and wish you
                                                the best in your future endeavors.</p>
                                        </div>
                                    @endif

                                    <!-- Request of COE -->
                                    @if ($employeeStatus === 'resigned')
                                        @if ($requestCertificate)
                                            <div class="mt-4">
                                                <button class="btn btn-primary btn-lg w-100 mb-2">
                                                    <i data-lucide="download" class="icon icon-large me-2"></i>
                                                    Download Certificate of Employee
                                                </button>

                                                <!-- BACK-END REPLACE: Remove disabled of the button if $requestCertificateStatus === 'issued' -->
                                            </div>

                                            <small>
                                                @if ($requestCertificateStatus === 'pending')
                                                    Your request is currently <span class="fw-bold text-info">pending</span>. Please check
                                                    back here
                                                    for further updates.
                                                @elseif ($requestCertificateStatus === 'issued')
                                                    Your Certificate of Employment has been successfully <span
                                                        class="text-primary fw-bold">issued</span>. You can now download it.
                                                @endif
                                            </small>
                                        @else
                                            <div class="mt-4">
                                                <button class="btn btn-primary btn-lg w-100">
                                                    <i data-lucide="file-badge" class="icon icon-large me-2"></i>
                                                    Request for Certificate of Employment
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    @endif
</div>