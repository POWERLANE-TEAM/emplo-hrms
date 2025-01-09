<section id="leaves" class="tab-section-employee">
    <div class="col-md-12 pt-3">
        <section class="row">
            <!-- Total Days -->

            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <p class="mb-0 text-primary fw-bold me-3" style="min-width: 100px;">
                        Select Year:
                    </p>
                    <div class="w-25">
                        <x-form.boxed-dropdown id="priority" :nonce="$nonce" :options="['2022' => '2022', '2023' => '2023', '2024' => '2024']" placeholder="Select year">
                        </x-form.boxed-dropdown>
                    </div>
                </div>
            </div>
        </section>

        <section class="row mt-3">
            <div class="col-md-12 border">
                <!-- BACK-END REPLACE: Employee's leaves balance  Remove the border class from the parent div. -->
                Table of the employee's leaves here. Refer to Figma.
            </div>
        </section>
    </div>
</section>