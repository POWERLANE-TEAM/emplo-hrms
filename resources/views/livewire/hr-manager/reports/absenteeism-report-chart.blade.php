@use(Illuminate\Support\Carbon)

<section class="mt-5">
    <h3 class="pb-1 fw-bold">{{ __('Employee Absenteeism Report') }}</h3>

    <!-- Chart Container -->
    <div wire:ignore x-data="{
        absenteeismData: @entangle('absenteeismData'),
        chartInstance: null,

        initializeAbsenteeismChart() {
            const ctx = document.getElementById('absenteeism-chart').getContext('2d');
            const absenteeismData = this.absenteeismData;
            
            if (ctx) {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                const monthlyData = Object.values(absenteeismData.monthly).map(item => item.absences);
                const months = Object.keys(absenteeismData.monthly).map(monthKey => {
                    const [year, month] = monthKey.split('-');
                    const date = new Date(`${year}-${month}-01`);
                    return date.toLocaleString('default', { month: 'long' });
                });

                this.chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Number of Absences',
                            data: monthlyData,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
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
                                        return 'Absences: ' + tooltipItem.raw;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }
    }" x-init="initializeAbsenteeismChart()" class="card border-primary p-4">

        <div class="col-md-12 h-25">
            <div class="overflow-auto visible-gray-scrollbar">
                <canvas id="absenteeism-chart"></canvas>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 bg-body-secondary w-100 p-4">
                        <div class="text-center">
                            <h5>Yearly Total</h5>
                            <h3 class="text-success fw-bold">
                                @foreach ($absenteeismData->yearly as $year => $data)
                                    {{ $data->total_absences }} absences
                                @endforeach
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-body-secondary w-100 p-4">
                        <div class="text-center">
                            <h5>Monthly Average</h5>
                            <h3 class="text-success fw-bold">
                                @foreach ($absenteeismData->yearly as $year => $data)
                                    {{ number_format($data->monthly_average, 1) }} absences per month
                                @endforeach
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-center align-items-center pt-4">

            <div class="card border-0 bg-body-secondary w-100 p-4">
                <div class="overflow-auto visible-gray-scrollbar absenteeism-table">
                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Number of Absences</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absenteeismData->monthly as $month => $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($month)->format('F') }}</td>
                                        <td>{{ $data->absences }}</td>
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