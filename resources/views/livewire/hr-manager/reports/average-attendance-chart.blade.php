<div class="mt-5">
    <h3 class="pb-1 fw-bold">Employee Attendance Rate Report</h3>

    <!-- Chart Container -->
    <div wire:ignore x-data="{
        attendanceData: @entangle('attendanceData'),
        chartInstance: null,

        initializeAttendanceChart() {
            const ctx = document.getElementById('attendance-rate-chart').getContext('2d');
            const attendanceData = this.attendanceData;
            
            if (ctx) {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                const monthlyData = Object.values(attendanceData.monthly).map(item => item.attendance_rate);
                const months = Object.keys(attendanceData.monthly).map(monthKey => {
                    const [year, month] = monthKey.split('-');
                    const date = new Date(`${year}-${month}-01`);
                    return date.toLocaleString('default', { month: 'long' });
                });

                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Attendance Rate (%)',
                            data: monthlyData,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return `Attendance Rate: ${tooltipItem.raw}%`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                min: 80,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Attendance Rate (%)'
                                }
                            }
                        }
                    }
                });
            }
        }
    }" x-init="initializeAttendanceChart()" class="card border-primary p-4">

        <div class="col-md-12 h-25">
            <div class="overflow-auto visible-gray-scrollbar">
                <canvas id="attendance-rate-chart"></canvas>
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-center align-items-center pt-4">
            <div class="card border-0 bg-body-secondary w-100 p-4">
                <div class="overflow-auto visible-gray-scrollbar attendance-avg-table">
                    <header>
                        <h4 class="text-primary fw-bold">Yearly Summary:
                            @foreach ($attendanceData['yearly'] as $year => $data)
                                {{ $data['attendance_rate'] }}% Average Attendance Rate
                            @endforeach
                        </h4>
                    </header>

                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Workdays</th>
                                    <th>Total Employees</th>
                                    <th>Days Attended</th>
                                    <th>Total Scheduled</th>
                                    <th>Attendance Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceData['monthly'] as $month => $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($month)->format('F') }}</td>
                                        <td>{{ $data['workdays'] }}</td>
                                        <td>{{ $data['total_employees'] }}</td>
                                        <td>{{ $data['days_attended'] }}</td>
                                        <td>{{ $data['total_scheduled'] }}</td>
                                        <td>{{ $data['attendance_rate'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>