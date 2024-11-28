<!-- BACK-END REPLACE Note:

 This component is currently designed for viewing only,
 which is why the checkboxes have the disabled attribute applied.
 It can be refactored to enable functionality for Supervisor,
 Head of Department, or HR roles, allowing them to interact with the checkboxes.
 This will make the component reusable across different user roles.
 
</div> -->

<div class="card border-primary mt-1 px-5 py-4 w-100 h-100">
    <section>
        <div class="text-primary fs-3 fw-bold text-center">
            Approvals
        </div>

        <!-- SECTION: upervisor and Dept.Head/Manager’s Approval -->
        <div class="pb-3 pt-4">
            <p class="fw-medium fs-5">Supervisor and Dept.Head/Manager’s Approval</p>

            <!-- Supervisor Approval -->
            <div class="ps-4 pe-2 py-3">
                <x-form.checkbox container_class="" :nonce="$nonce" id="supervisor_approval" name="supervisor_approval"
                    class="checkbox checkbox-primary" disabled> <!-- BACK-END Replace: Status of Supervisor approval. Checked attribute if approved. -->

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
                    class="checkbox checkbox-primary" disabled> <!-- BACK-END Replace: Status of Head Dept approval. Checked attribute if approved. -->

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
                    class="checkbox checkbox-primary" disabled> <!-- BACK-END Replace: Status of HR Staff approval. Checked attribute if approved. -->

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
                            name="hr_head_approval" class="checkbox checkbox-primary" disabled> <!-- BACK-END Replace: Status of HR Staff approval. Checked attribute if approved. -->

                            <x-slot:label>
                                <div class="d-flex flex-column">
                                    <div class="fs-5">Kilnsey, Maria H.</div>
                                    <!-- BACK-END Replace: HR Department Head Name -->
                                    <div class="text-primary">HRD Department</div>
                                </div>
                            </x-slot:label>
                        </x-form.checkbox>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>