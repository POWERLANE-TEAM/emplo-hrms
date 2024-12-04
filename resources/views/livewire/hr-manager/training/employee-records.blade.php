<div>
    <section class="row pt-4">
        <div class="col-6">
            <label class="ps-1 mb-2 fw-semibold text-primary fs-5"> Employee Name </label>

            <div class="row">
                <div class="col-md-8">
                    <!-- BACK-END REPLACE: All Employees -->
                    <x-form.boxed-selectpicker id="incident_type" :nonce="$nonce" :required="true"
                        :options="['employee_1' => 'Cristian Manalang', 'employee_2' => 'Jobert Owen']"
                        placeholder="Select employee">
                    </x-form.boxed-selectpicker>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="hover-opacity pe-auto">
                        <i data-lucide="user-2" class="icon icon-slarge ms-2 text-blue-info"></i>
                        <!-- BACK-END REPLACE: Link to the current employee's profile -->
                        <a href="#" class="text-link-blue text-decoration-underline fs-5">
                            View Information
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 pt-2 d-flex align-items-center justify-content-end">
            <button onclick="openModal('addTrainingRecord')" class="btn btn-primary">
                <i data-lucide="plus-circle" class="icon icon-large me-2"></i> Add New Training</button>
        </div>
    </section>

    <section class="my-2">
        <!-- BACK-END REPLACE: Table of the selected employee's training records. -->
    </section>
</div>