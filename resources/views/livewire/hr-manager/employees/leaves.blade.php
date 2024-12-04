<section id="leaves" class="tab-section">
    <div class="col-md-12 pt-3">
        <section class="row">
            <!-- Total Days -->
            <div class="col-md-3 mb-3">
                <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <p class="fs-3 fw-bold">20</p>
                        <p class="fs-5">Total Leaves Days</p>
                    </div>
                </div>
            </div>

            <!-- SECTION: Leave Balance Card -->
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white border-0 py-4 px-5 h-100">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <p class="fs-3 fw-bold">6</p>
                        <p class="fs-5">Days Left</p>
                    </div>
                </div>
            </div>

            <!-- SECTION: Next Payslips Card -->
            <div class="col-md-3 mb-3">
                <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <p class="fs-3 fw-bold">14</p>
                        <p class="fs-5">Days Taken</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3 d-flex justify-content-center align-items-center flex-column">
                <!-- BACK-END Replace: Year options. Default is current year. -->
                <x-form.boxed-dropdown id="priority" label="{{ __('Select Year') }}" :nonce="$nonce" :options="['2022' => '2022', '2023' => '2023', '2024' => '2024']" placeholder="Select year">
                </x-form.boxed-dropdown>
            </div>
        </section>

        <section class="row mt-3">
            <div class="col-md-12 border">
                <!-- BACK-END REPLACE: Employee's leaves.  Remove the border class from the parent div. -->
                Table of the employee's overtime summay forms here. Refer to Figma.
            </div>
        </section>
    </div>
</section>