<div>

    <!-- Overtime Chart -->
    <section>
        <div class="row">
            <div class="col-md-8 border h-25">
                <div>
                    <canvas id="overtime-chart"></canvas>
                </div>
            </div>

            <div class="col-md-4 border">
                Details
            </div>
        </div>
    </section>

    <!-- Attendance Chart -->
    <section class="mt-5">
        <div class="row">
            <div class="col-md-8 border h-25">
                <div>
                    <canvas id="attendance-chart"></canvas>
                </div>
            </div>

            <div class="col-md-4 border">
                Details
            </div>
        </div>
    </section>

    <!-- Issue Resolution Chart -->
    <section>
        <div class="row">
            <div class="col-md-8 border h-25">
                <div>
                    <canvas id="issue-resolution-chart"></canvas>
                </div>
            </div>

            <div class="col-md-4 border">
                Details
            </div>
        </div>
    </section>

    <!-- Leave Utilization Chart -->
    <section>
        <div class="row">
            <div class="col-md-8 border h-25">
                <div>
                <select id="leave-type-dropdown" class="form-select mb-3">
    <option value="all" selected>All Leave Types</option>
    <option value="sick">Sick Leave</option>
    <option value="vacation">Vacation Leave</option>
    <option value="paternity">Paternity Leave</option>
    <option value="maternity">Maternity Leave</option>
</select>
                    <canvas id="leave-utilization-chart"></canvas>
                </div>
            </div>

            <div class="col-md-4 border">
                Details
            </div>
        </div>
    </section>

</div>