<section class="mt-5">
    <h3 class="pb-1 fw-bold">Issue Resolution Time Rate</h3>

    <!-- Chart Container -->
    <div wire:ignore x-data="{
        issueResolutionData: @entangle('issueResolutionData'),
        chartInstance: null,

        initializeIssueResolutionChart() {
            const ctx = document.getElementById('issue-resolution-chart').getContext('2d');
            const issueResolutionData = this.issueResolutionData;
            
            if (ctx) {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                const monthlyData = Object.values(issueResolutionData.monthly).map(item => item.average_days.toFixed(1));
                const months = Object.keys(issueResolutionData.monthly).map(monthKey => {
                    const [year, month] = monthKey.split('-');
                    const date = new Date(`${year}-${month}-01`);
                    return date.toLocaleString('default', { month: 'long' });
                });

                const yearlyData = Object.values(issueResolutionData.yearly).map(item => item.average_days);
                
                // Chart.js Setup
                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels:  months,
                        datasets: [{
                            label: 'Average Resolution Time (days)',
                            data: monthlyData, // Use the yearly average resolution time
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
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
                                        return 'Resolution Time: ' + tooltipItem.raw + ' days';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        },

        // Watch for changes in issueResolutionData
        watch: {
            issueResolutionData: {
                handler() {
                    this.initializeIssueResolutionChart();
                },
                immediate: true
            }
        }

    }" x-init="initializeIssueResolutionChart()" class="card border-primary p-4">


        <div class="col-md-12 h-25">
            <div class="overflow-auto visible-gray-scrollbar">
                <canvas id="issue-resolution-chart"></canvas>
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-center align-items-center pt-4">
            <div class="card border-0 bg-body-secondary w-100 p-4" height="50px">
                <div class="overflow-auto visible-gray-scrollbar issue-table">
                    <header>
                        <h4 class="text-primary fw-bold">Yearly Average Summary:
                            @foreach ($issueResolutionData['yearly'] as $year => $data)
                                {{ number_format($data['average_days'], 2) }} days resolution time
                            @endforeach
                        </h4>
                    </header>

                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total Issues</th>
                                    <th>Average Resolution Time (Days)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($issueResolutionData['monthly'] as $month => $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($month)->format('F') }}</td>
                                        <td>{{ $data['count'] }}</td>
                                        <td>{{ number_format($data['average_days'], 2) }} days</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>