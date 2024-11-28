<!-- BACK-END REPLACE Note:

 This component is currently designed for viewing only, just like the approvals component.
 It can be refactored to enable approve/reject functionality for Supervisor, Head of Department, or HR roles.
 This will make the component reusable across different user roles.

  I've temporarily commented out the buttons. Feel free to uncomment them if this is the same component that'll
  be used for the approval for the other users.
 
</div> -->

<div>
    <div class="row px-3 mb-4">

        <!-- Type of Leave -->
        <div class="row">
            <div class="col">

                <div class="mb-3">
                    <x-form.display.boxed-input-display label="Type of Leave" data="Sick Leave" />
                    <!-- BACK-END REPLACE: Leave type -->
                </div>

            </div>
        </div>

        <!-- Calendar: Start Date & End Date -->
        <div class="row">
            <div class="col">
                <x-form.display.boxed-input-display label="Start Date" data="11/21/2024" />
                <!-- BACK-END REPLACE: Start Date -->
            </div>

            <div class="col">
                <x-form.display.boxed-input-display label="Start Date" data="11/30/2024" />
                <!-- BACK-END REPLACE: End  Date Date -->
            </div>
        </div>

        <!-- Reason for Leave -->
        <div class="row">
            <div class="col">
                <x-form.display.boxed-input-display label="Reason for leave"
                    data="Diagnosed with an acute case of 'I Can't Even.' Doctor's orders: binge-watch cat videos and avoid all human interaction for 24 hours.  My doctor insists I take an extended break to recalibrate my life, catch up on sleep, and pretend to be a productive member of society again. My symptoms include excessive scrolling through memes, procrastination, and a complete inability to look at spreadsheets without fainting. I'll be back when I remember how to function like a normal human." />
                <!-- BACK-END REPLACE: End  Date Date -->
            </div>
        </div>

        <!-- Reason for Leave -->
        <div class="pe-4 my-2">
            <div class="col-md-12 pe-2">
                <div class="callout callout-success bg-body-tertiary">
                    <div class="fs-5 px-2">Total leave days requested:
                        <span class="fw-bold text-primary">10</span>
                        <!-- Back-end Replace: Total count. This should be a client-side live response. -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve/Reject Buttons 
        <div class="container pe-4 mt-4">
            <div class="row">
                <div class="col-6 pe-2">
                    <button type="submit" name="submit" class="btn btn-lg btn-danger w-100">Reject</button>
                </div>
                <div class="col-6">
                    <button type="submit" name="submit" class="btn btn-lg btn-primary w-100">Approve</button>
                </div>
            </div>
        </div> -->

    </div>
</div>