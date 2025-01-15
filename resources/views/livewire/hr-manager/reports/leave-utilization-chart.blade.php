<section class="mt-5">
    <h3 class="pb-1 fw-bold">Leave Utilization Rate</h3>
    <div wire:ignore x-data="{
        leaveData: @entangle('leaveData'),
        selectedLeaveType: 'all',
        chartInstance: null,

        initializeLeaveChart() {
            const leaveTypeData = this.leaveData[this.selectedLeaveType] || this.leaveData['All Leave Types'];
            const ctx = document.getElementById('leave-utilization-chart').getContext('2d');
            
            if (ctx) {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                this.chartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Used', 'Remaining'],
                        datasets: [{
                            data: [
                                (leaveTypeData.used / leaveTypeData.total) * 100,
                                ((leaveTypeData.total - leaveTypeData.used) / leaveTypeData.total) * 100
                            ],
                            backgroundColor: ['rgba(0, 98, 144, 0.39)', 'rgb(179, 217, 137)'],
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
                                        const percentage = tooltipItem.raw;
                                        const actualDays = tooltipItem.label === 'Used' ? leaveTypeData.used : (leaveTypeData.total - leaveTypeData.used);
                                        return `${tooltipItem.label}: ${percentage.toFixed(2)}% (${actualDays} days)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        },

        // Watch for changes in selectedLeaveType to update the chart
        watchSelectedLeaveType() {
            this.initializeLeaveChart();
        }
    }" x-init="initializeLeaveChart()" class="card border-primary p-4">

        <div class="row">
            <div class="col-md-7 h-25">
                <div class="w-50 pb-4">
                    <select id="leave-type-dropdown" class="form-control form-select border ps-3 rounded pe-5"
                        x-model="selectedLeaveType" @change="watchSelectedLeaveType">
                        @foreach($leaveData as $type => $data)
                            <option value="{{ Str::slug($type) }}">{{ ucfirst($type) }} Leave</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <canvas id="leave-utilization-chart"></canvas>
                </div>
            </div>

            <div class="col-md-4 d-flex justify-content-center align-items-center pt-4">
                <div class="card border-0 bg-body-secondary w-100 h-75 p-4">
                    <div class="overflow-auto visible-gray-scrollbar">
                        <header>
                            <h4 class="text-primary fw-bold">Leave Utilization Rate</h4>
                        </header>
                        <div class="px-1">
                            <ul>
                                @foreach ($leaveData as $leaveType => $data)
                                    @php
                                        $percentage = ($data['used'] / $data['total']) * 100;
                                    @endphp

                                    <li class="pb-2">
                                        {{ ucfirst($leaveType) }} Leave: <strong>{{ round($percentage, 2) }}%</strong>
                                        <ul>
                                                <li>{{ $data['used'] }} days used out of {{ $data['total'] }}</li>
                                        </ul>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- @script
<script>
    window.addEventListener('year-changed', event => {
        console.log('Year changed event detected:', event.detail);
        @this.set('selectedYear', event.detail);
    });
</script>
@endscript --}}