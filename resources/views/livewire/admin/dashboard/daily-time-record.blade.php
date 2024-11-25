<div class="col-md-5 flex" x-cloak>
    <div class="card p-4">
        <div class="table-attendance-cont">
            <header class="fs-4 fw-bold" role="heading" aria-level="2">
                <i class="bi bi-circle-fill text-danger fs-4"></i>
                <span class="text-danger text-uppercase fw-bold">Live</span>
                Daily Time Record
            </header>

            <table class="table table-borderless table-attendance-list">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>

                <!-- BACK-END REPLACE: DTI from Database. Limit to 5. -->
                <tbody>
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td>{{ fake()->name() }}</td>
                            <td>{{ fake()->time() }}</td>
                            <td>{{ fake()->time() }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <!-- Redirect Link: To Attendance -->
            <div class="col-12 px-5">
                <x-buttons.view-link-btn link="#" text="View All" />
            </div>
        </div>
    </div>
</div>