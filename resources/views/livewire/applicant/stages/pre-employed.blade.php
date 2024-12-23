@use('App\Http\Helpers\ChartJs')

@php
    [$statusText, $statusColor] = $this->overallStatus;
@endphp

<div>
    <section class="mb-5">
        <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
            <span class="fs-2 fw-bold ps-1 pe-3">Pre-Employment Requirements</span>
            <x-status-badge :color="$statusColor">{{ $statusText }}</x-status-badge>
        </header>

        <div class="row flex-md-nowrap gap-5">
            <div class="col-md-6 p-3">
                <div class="position-relative mx-auto">
                    <canvas id="chartProgress" class=""></canvas>
                </div>

            </div>
            <section class="d-flex flex-column col-md-6 px-5 gap-4">
                <header class="fw-semibold fs-5 ">
                    Status Metric
                </header>
                <div class=" d-flex flex-column gap-3">
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-info  d-inline" data-lucide="badge-info"></i>
                        </span>
                        <span>Pending for review: </span>
                        <b>{{ $pendingDocuments->count() }}</b>
                    </div>
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-success  d-inline" data-lucide="badge-check"></i>
                        </span>
                        <span>Verified documents: </span>
                        <b>{{ $verifiedDocuments->count() }}</b>
                    </div>
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-danger  d-inline" data-lucide="badge-alert"></i>
                        </span>
                        <span>Awaiting Resubmission: </span>
                        <b>{{ $rejectedDocuments->count() }}</b>
                    </div>

                </div>
                <small>
                    <i><b>Note: </b>Status updates will be provided periodically. Review of pending documents may take
                        1-3
                        days.</i>
                </small>
            </section>
        </div>
    </section>

    <nav class="w-100 d-flex mb-5">
        <a href="/preemploy" class="btn btn-primary btn-lg mx-auto px-5 text-capitalize"> <span><i
                    class="icon p-1 mx-2 d-inline" data-lucide="plus-circle"></i></span>Go to submission
            page</a>
    </nav>
</div>

@script
    <script nonce="{{ $nonce }}">
        document.addEventListener("livewire:navigated", (event) => {

            const pendingDocumentCount = Math.max(0, Math.floor(@json($pendingDocuments->count())));
            const verifiedDocumentCount = Math.max(0, Math.floor(@json($verifiedDocuments->count())));
            const rejectedDocumentCount = Math.max(16, Math.floor(@json($rejectedDocuments->count())));
            const totalRequirementCount = Math.max(0, Math.floor(@json($this->premploymentRequirements->count())));

            const ROOT = document.documentElement;
            const ROOT_STYLES = getComputedStyle(ROOT);
            const VERIFIED_COLOR = ROOT_STYLES.getPropertyValue('--bs-primary').trim();
            const PENDING_COLOR = ROOT_STYLES.getPropertyValue('--bs-info').trim();
            const REJECTED_COLOR = ROOT_STYLES.getPropertyValue('--bs-danger').trim();
            const DEFAULT_COLOR = '#E5E5E5';
            const body = document.body;
            const currentTheme = body.getAttribute('data-bs-theme');

            let primaryTextColor = window.validateTextColor(window.textColor.bodyColor) ? window.textColor
                .bodyColor : 'black';
            let secondaryTextColor = window.validateTextColor(window.textColor.bodySecondaryColor) ? window
                .textColor.bodySecondaryColor : 'black';

            const VIEW_WIDTH = document.documentElement.clientWidth;
            const BASE_FONT_SIZE = parseInt(ROOT_STYLES.fontSize)
            const RFS = VIEW_WIDTH * 0.00125;


            function caclulateFillPercent(docCount, total = totalRequirementCount) {
                return (docCount / total) * 100;
            }

            pendingDocumentPercent = caclulateFillPercent(pendingDocumentCount);
            verfiedDocumentPercent = caclulateFillPercent(verifiedDocumentCount);
            rejectedDocumentPercent = caclulateFillPercent(rejectedDocumentCount);

            let submittedCount = pendingDocumentCount + verifiedDocumentCount + rejectedDocumentCount;

            if (submittedCount > totalRequirementCount) {
                console.error('Submitted count exceeds total requirement count');
            }

            let unsubmittedCount = totalRequirementCount - (verifiedDocumentCount + pendingDocumentCount +
                rejectedDocumentCount);

            let remainingPercent = caclulateFillPercent(verifiedDocumentCount + pendingDocumentCount +
                rejectedDocumentCount);

            let percentages = [verfiedDocumentPercent, pendingDocumentPercent, rejectedDocumentPercent];

            let forTooltip = [verifiedDocumentCount, pendingDocumentCount, rejectedDocumentCount,
                unsubmittedCount
            ];

            let dataLabels = ['Completed', 'Pending Review', 'Rejected ', 'Remaining'];

            tooltipData = forTooltip.reduce((acc, count, index) => (count > 0 ? {
                ...acc,
                [index]: count
            } : acc), {});

            let submittedData = percentages.reduce((acc, count, index) => (count > 0 ? {
                ...acc,
                [index]: count
            } : acc), {});

            var myChartCircle = new Chart('chartProgress', {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: percentages.concat(100),
                        // backgroundColor: function(context) {
                        //     const CHART = context.chart;
                        //     const {
                        //         ctx,
                        //         chartArea
                        //     } = CHART;
                        //     if (!chartArea) {
                        //         // This can happen if the chart is not yet initialized
                        //         return null;
                        //     }

                        //     // return [
                        //     //     window.createGradient(ctx, chartArea, PENDING_COLOR,
                        //     //         pendingDocumentPercent),
                        //     //     window.createGradient(ctx, chartArea, VERIFIED_COLOR,
                        //     //         verfiedDocumentPercent),
                        //     //     window.createGradient(ctx, chartArea, REJECTED_COLOR,
                        //     //         rejectedDocumentPercent),
                        //     //     DEFAULT_COLOR // Default color for remaining percentage
                        //     // ];

                        //     const dataIndex = context.dataIndex;
                        //     const colors = [PENDING_COLOR, VERIFIED_COLOR, REJECTED_COLOR,
                        //         DEFAULT_COLOR
                        //     ];

                        //     return createGradient(ctx, chartArea, colors[dataIndex], 0, 0.55);
                        // },


                        backgroundColor: {!! ChartJs::pieGenerateBgColors(
                            ['verifiedDocumentCount', 'pendingDocumentCount', 'rejectedDocumentCount'],
                            16,
                            ['VERIFIED_COLOR', 'PENDING_COLOR', 'REJECTED_COLOR'],
                            'DEFAULT_COLOR',
                        ) !!},
                        //

                        borderWidth: 0,
                        hoverBorderWidth: 3
                    }],
                    labels: dataLabels,
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '82%',
                    // cutout: '1%',
                    rotation: Math.PI / 2,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Submitted Requirements',
                            color: primaryTextColor,
                            padding: {
                                bottom: 48
                            },
                            font: {
                                size: 18,
                                family: 'Figtree',
                                weight: 'bold'
                            }
                        },
                        annotation: {

                            annotations: {
                                labelfor: {
                                    type: 'label',
                                    xValue: 2.5,
                                    yValue: 60,
                                    yAdjust: 50,
                                    content: ['Submitted'],
                                    color: secondaryTextColor,
                                    font: {
                                        size: 14,
                                        family: 'Figtree',
                                        weight: 'bold'
                                    }
                                },

                                labelData: {
                                    type: 'label',
                                    drawTime: 'afterDraw',
                                    xValue: 2.5,
                                    yValue: 60,
                                    yAdjust: 80,
                                    content: [`${verifiedDocumentCount} out of ${totalRequirementCount}`],
                                    color: primaryTextColor,
                                    font: {
                                        size: 24,
                                        family: 'Figtree',
                                        weight: 'bold'
                                    }
                                }

                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ((tooltipItem, data) => {
                                    console.log(tooltipItem);
                                    console.log(data);
                                    console.log(tooltipItem.dataIndex);
                                    console.log(tooltipData[tooltipItem.dataIndex]);
                                    return tooltipData[tooltipItem.dataIndex];
                                })
                            }
                        }

                    }

                },
                plugins: [{
                        {!! ChartJs::pieGenerateLabels() !!}
                    },


                ]
            });

            setTimeout(() => {
                /* https://www.chartjs.org/docs/latest/developers/api.html#update-mode */
                console.log(myChartCircle.options.plugins.annotation.annotations.labelData.content[0]);
                // myChartCircle.options.plugins.annotation.annotations.labelData.content[0] = 'Updated';
                // myChartCircle.options.plugins.annotation.annotations.labelData.color = 'red';
                myChartCircle.options.animation = false;
                myChartCircle.update('show');
            }, 3000);
        });
    </script>
@endscript
