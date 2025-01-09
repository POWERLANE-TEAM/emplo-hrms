<div class="mt-5">
    <h3 class="fw-bold pb-1">Employee Retention & Turnover Rate</h3>

    <!-- Chart Container -->
    <div wire:ignore x-data="{
        retentionData: @entangle('retentionData'),
        chartInstance: null,

        initializeRetentionChart() {
            const ctx = document.getElementById('retention-turnover-chart').getContext('2d');
            
            if (ctx) {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                this.chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Retention & Turnover'],
                        datasets: [{
                            label: 'Retention Rate',
                            data: [this.retentionData.retention_rate],
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Turnover Rate',
                            data: [this.retentionData.turnover_rate],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1  
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                stacked: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Percentage (%)'
                                }
                            },
                            y: {
                                stacked: true
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }" x-init="initializeRetentionChart()" class="card border-primary p-4">

        <div class="col-md-12">
            <div class="overflow-auto visible-gray-scrollbar">
                <canvas id="retention-turnover-chart"></canvas>
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-center align-items-center pt-4">
            <div class="card border-0 bg-body-secondary w-100 p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h5 class="text-primary">Total Employees</h5>
                            <h3>{{ $retentionData['total_start'] }}</h3>
                            <p>Starting count for {{ $retentionData['year'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h5 class="text-primary">Retention Rate</h5>
                            <h3 class="text-success fw-bold">{{ $retentionData['retention_rate'] }}%</h3>
                            <p>{{ $retentionData['total_stayed'] }} employees stayed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h5 class="text-primary">Turnover Rate</h5>
                            <h3 class="text-danger fw-bold">{{ $retentionData['turnover_rate'] }}%</h3>
                            <p>{{ $retentionData['total_left'] }} employees left</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    window.addEventListener('year-changed', event => {
        console.log('Year changed event detected:', event.detail);
        @this.set('selectedYear', event.detail);
    });
</script>
@endscript