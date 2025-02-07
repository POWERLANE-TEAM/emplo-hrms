@use('Carbon\Carbon')
@use('App\Http\Helpers\Timezone')
<div>

    <!-- BACK-END REPLACE: Replace placeholder datas. -->

    @php
        // ===========================
        // Resignation Letter State
        // ===========================
        $isEmpty = !$hasResignation;
        // Boolean flag indicating if the resignation letter is submitted.
        // If true, it triggers the empty state (no letter submitted).
        // Toggle this flag if a resignation letter is submitted.

        $determinedOn = optional($resignation)->finalDecisionAt;
        // Date when HR processed and made a decision on the resignation (approval/rejection).
        // Set when HR approves or rejects the resignation.

        $status = optional(optional($resignation)->resignationStatus)->resignation_status_name;

        // Current status of the resignation letter submission.
        // Possible values: 'pending' (letter submitted but not yet reviewed),
        // 'approved' (resignation letter approved by HR), or 'rejected' (resignation letter rejected).

        $hasComments = !empty(optional($resignation)->initial_approver_comments);
        // Indicates whether HR has left comments on the resignation letter.
        // Set to true if HR provided comments, false if no comments are given.


        // ===========================
        // Employee Status
        // ===========================
        $employeeStatus = strtolower($employee->status->emp_status_name);

        // The current employment status of the employee.
        // This is updated to 'resigned' if the resignation letter is approved.

        $resignee = optional($resignation)->resignee;

        // ===========================
        // COE Issuance
        // ===========================

        $certificateStatus = optional($coeReq)->emp_coe_doc_id  ? 'issued' : 'pending';
        // Status of the COE issuance.
        // Possible values: 'pending' (is being processed),
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
                        <a href="{{route($routePrefix.'.separation.resignation.create')}}"
                            class="btn btn-primary btn-lg w-25 d-flex align-items-center justify-content-center mx-auto">
                            <i data-lucide="file-plus-2" class="icon icon-large me-2"></i>
                            File Resignation Letter
                        </a>
                    </div>

                    <!-- REPLACE STATIC PAGE LINK: separation Process Policy -->
                    <div class="col-12 text-center">
                        <a href="/information-centre?section=seperation-process" id="toggle-information" class="text-link-blue text-decoration-underline fs-7">
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
                                allowfullscreen='yes' src="{{ Storage::url($resignation->resignationLetter->file_path) }}"
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
                                {{-- TODO handle when null bug on Carbon --}}
                                <p class="fw-bold mt-3 fs-5">Submitted on: <span class="fw-medium">{{ Carbon::parse($resignation->filed_at)->setTimezone(Timezone::get())->format('F j, Y') }}</span>
                                </p>

                                <!-- Determined on. If determinedOn date is not null -->
                                {{-- TODO handle when null bug on Carbon --}}
                                @if (!empty($determinedOn))
                                    <p class="fw-bold mt-3 fs-5">Determined on:
                                        <span
                                            class="fw-medium">{{ when($determinedOn, Carbon::parse($determinedOn)->setTimezone(Timezone::get())->format('F j, Y')) }}</span>
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
                                            <p class="fw-bold fs-5">{{$resignation->initial_approver_comments}}</p>
                                        </div>
                                    @endif

                                    <!-- Request of COE -->
                                    @if ($employeeStatus === 'resigned')
                                        <div class="mt-4">
                                            <button class="btn btn-primary btn-lg w-100 mb-2" {{when($certificateStatus != 'issued', 'disabled') }} wire:click="download">
                                                <i data-lucide="download" class="icon icon-large me-2"></i>
                                                Download Certificate of Employee
                                            </button>

                                            <!-- BACK-END REPLACE: Remove disabled of the button if $certificateStatus === 'issued' -->
                                        </div>

                                        <small>
                                            @if ($certificateStatus === 'pending')
                                                Your certificate is currently <span class="fw-bold text-info">pending</span>. Your certificate will be processed within 15 days of completing your clearance rendering.
                                            @elseif ($certificateStatus === 'issued')
                                                Your Certificate of Employment has been successfully <span
                                                    class="text-primary fw-bold">issued</span>. You can now download it.
                                            @endif
                                        </small>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    @endif
</div>
