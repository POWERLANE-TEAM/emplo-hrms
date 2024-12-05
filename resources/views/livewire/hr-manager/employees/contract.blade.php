<section id="contract" class="tab-section">
    <section class="px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="text-primary fs-3 fw-bold d-flex align-items-center"">
                    Contract Overview
                </div>
            </div>
        </div>

        <!-- BACK-END REPLACE NOTE:
        If the status of the employee is regular, then the expiration date is indefinite.
        If probationary, only then there will be an expiration date. -->

        <section class=" row mt-3">
            <div class="col-md-4">
                <!-- BACK-END REPLACE: Employment, Status, Monthly Salary -->
                <p class="pb-2"><b>Employment: </b>Regular</p>
                <p class="pb-2"><b>Status: </b>Active</p>
                <p class="pb-2"><b>Monthly Salary: </b> ₱15,000</p>
            </div>

            <div class="col-md-4">
                <!-- BACK-END REPLACE: Start Date, Expiration Date -->
                <p class="pb-2"><b>Start Date: </b>January 3, 2024</p>
                <p class="pb-2"><b>Status: </b>Indefinite</p>
            </div>

            <div class="col-md-4">
                <div class="d-flex flex-column">

                    <!-- Upload New Contract -->
                    <label for="file-upload" class="btn btn-primary btn-lg w-100 mb-2 d-flex align-items-center justify-content-center">
                        <i data-lucide="upload" class="icon icon-large me-2"></i>
                        Upload New Contract
                    </label>

                            <!-- Add Addendum -->
                    <label for="file-upload" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                        <i data-lucide="plus" class="icon icon-large me-2"></i>
                        Add an Addendum
                        </label>
                    <input type="file" id="file-upload" class="d-none" />
                </div>
            </div>
        </section>

        <section class="row pt-3">
            <div class="col border">
                <!-- BACK-END Replace: Employee's Documents Table. Remove the border class from the parent div. -->
                Table of all contracts/addendum. Refer to Figma.
            </div>
        </section>
    </section>
</section>