<div class="card border-primary mt-1 px-5 py-4 w-100">

    <!-- Overview Tab Section-->
    <section id="overview" class="tab-section">

        <!-- Section Title -->
        <div class="text-primary fs-3 fw-bold text-center">
            Overview
        </div>

        <!-- SECTION: Final Rating & Performance Scale -->
        <div class="text-center pt-3 pb-5">
            <p class="fw-bold fs-4">3.8 - Exceeds Expectation</p> <!-- BACK-END Replace: Rating & Scale -->
            <p class="text-muted">Final Rating & Performance Scale</p>
        </div>

        <!-- SECTION: Supervisor’s Final Recommendation -->
        <div class="pb-3">
            <p class="fw-medium fs-5">Supervisor’s Final Recommendation</p>

            <!-- BACK-END Replace: Status Icon & Recommendation -->
            <div class="row py-3 d-flex align-items-center">
                <div class="col-2 d-flex justify-content-end p-0">
                    <i data-lucide="badge-check" class="icon icon-xlarge text-primary"></i>
                </div>
                <div class="col justify-content-start">
                    <span class="fs-5">Recommended to become a regular employee</span>
                </div>
            </div>
        </div>

        <!-- SECTION: Main Approvals -->
        <div class="pb-3">
            <p class="fw-medium fs-5">Main Approvals</p>

            <!-- Supervisor Approval -->
            <div class="ps-4 pe-2 py-3">
                <x-form.checkbox container_class="" :nonce="$nonce" id="supervisor_approval" name="supervisor_approval"
                    class="checkbox checkbox-primary" checked>

                    <x-slot:label>
                        <div class="d-flex flex-column">
                            <div class="fs-5">Augistina, De Leon C.</div> <!-- BACK-END Replace: Supervisor Name -->
                            <div class="text-primary">Supervisor/Evaluator</div>
                        </div>
                    </x-slot:label>
                </x-form.checkbox>
            </div>

            <!-- Head Department Approval -->
            <div class="ps-4 pe-2 py-2">
                <x-form.checkbox container_class="" :nonce="$nonce" id="head_dept_approval" name="head_dept_approval"
                    class="checkbox checkbox-primary" checked>

                    <x-slot:label>
                        <div class="d-flex flex-column">
                            <div class="fs-5">Swift, Taylor A.</div>
                            <!-- BACK-END Replace: Job Family (Dept) Head Name -->
                            <div class="text-primary">Supervisor/Evaluator</div>
                        </div>
                    </x-slot:label>
                </x-form.checkbox>
            </div>
        </div>

        <!-- SECTION: HR Approvals -->
        <div class="pb-3">
            <p class="fw-medium fs-5">Human Resources Department</p>

            <!-- Supervisor Approval -->
            <div class="ps-4 pe-2 py-3">
                <x-form.checkbox container_class="" :nonce="$nonce" id="hr_staff_approval" name="hr_staff_approval"
                    class="checkbox checkbox-primary" checked>

                    <x-slot:label>
                        <div class="d-flex flex-column">
                            <div class="fs-5">Ruiz, Edmark P.</div> <!-- BACK-END Replace: HR Staff -->
                            <div class="text-primary">HR Staff</div>
                        </div>
                    </x-slot:label>
                </x-form.checkbox>
            </div>

            <!-- Head Department Approval -->
            <div class="ps-4 pe-2 py-2">
                <div class="row">
                    <div class="col-7">
                        <x-form.checkbox container_class="" :nonce="$nonce" id="hr_head_approval"
                            name="hr_head_approval" class="checkbox checkbox-primary">

                            <x-slot:label>
                                <div class="d-flex flex-column">
                                    <div class="fs-5">Kilnsey, Maria H.</div>
                                    <!-- BACK-END Replace: HR Department Head Name -->
                                    <div class="text-primary">HRD Department</div>
                                </div>
                            </x-slot:label>
                        </x-form.checkbox>
                    </div>

                    <!-- Reusable Component: Pending Status.
                    This should only appear if the approval of anyone is still pending. -->

                    <div class="col-3">
                        <x-status-badge color="info">Pending</x-status-badge>
                    </div>
                </div>
            </div>

            <div class="ps-4 pe-2 py-2">

            </div>
        </div>

        <div>
            <button onclick="openModal('approvalHistoryModal')" class="btn fw-medium underline hover-opacity underline">Approval History</button>
        </div>
    </section>

    <!-- Attendance Tab Section -->
    <section id="attendance" class="tab-section">
        Attendance content goes here.
    </section>

    <!-- Comments Tab Section -->
    <section id="comments" class="tab-section">
        Comments content goes here.
    </section>
</div>