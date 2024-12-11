
import "../../css/applicant/dashboard.css";
import '../script.js';
import './script.js';
import initLucideIcons from '../icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from '../global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();

    const ROOT = document.documentElement;
    const ROOT_STYLES = getComputedStyle(ROOT);
    const PRIMARY_COLOR = ROOT_STYLES.getPropertyValue('--bs-primary').trim();
    const body = document.body;
    const currentTheme = body.getAttribute('data-bs-theme');

    let fontColor = currentTheme === 'light' ? 'black' : 'white';

    const VIEW_WIDTH = document.documentElement.clientWidth;
    const BASE_FONT_SIZE = parseInt(ROOT_STYLES.fontSize)
    const RFS = VIEW_WIDTH * 0.00125;


    function createGradient(ctx, chartArea, color) {
        const GRADIENT = ctx.createLinearGradient(0, 0, chartArea.width, 0);
        GRADIENT.addColorStop(0, '#FFFFFF');
        GRADIENT.addColorStop(1, color);
        return GRADIENT;
    }

    const fillPercent = (11 / 18) * 100;

    let submittedData = {
        '0': ' 11',
        '1': ' 7'
    }

    var myChartCircle = new Chart('chartProgress', {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [fillPercent, 100],
                backgroundColor: function (context) {
                    const CHART = context.chart;
                    const {
                        ctx,
                        chartArea
                    } = CHART;
                    if (!chartArea) {
                        // This can happen if the chart is not yet initialized
                        return null;
                    }
                    return [
                        createGradient(ctx, chartArea, PRIMARY_COLOR),
                        '#E5E5E5' // Default color for remaining percentage
                    ];
                },
                borderWidth: 0,
                hoverBorderWidth: 3
            }],
            labels: ['Completed', 'Remaining']
        },
        options: {
            maintainAspectRatio: false,
            cutout: '82%',
            rotation: Math.PI / 2,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Submitted Requirements',
                    color: 'black',
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
                            color: 'gray',
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
                            content: ['11 out of 18'],
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
                            console.log(tooltipItem)
                            return submittedData[tooltipItem.dataIndex];
                        })
                    }
                }

            }

        },
        plugins: [{
            beforeInit: (CHART) => {
                const DATASET = CHART.data.datasets[0];
                DATASET.data = [DATASET.data[0], 100 - DATASET.data[
                    0]]; // Calculate remaining percentage
            }
        },

            /* Original code from peter */
            // {
            //     beforeDraw: (CHART) => {
            //         var width = CHART.width,
            //             height = CHART.height,
            //             ctx = CHART.ctx;
            //         ctx.restore();
            //         var fontSize = (height / 200).toFixed(2);
            //         // var fontSize = RFS;
            //         ctx.font = "bold " + fontSize +
            //             "em figtree, sans-serif"; // Bold and Montserrat
            //         ctx.fillStyle = fontColor;
            //         ctx.textBaseline = "middle";
            //         var text = `11 out of 18`,
            //             textX = Math.round((width - ctx.measureText(text).width) / 2),
            //             textY = height / 1.75;
            //         ctx.fillText(text, textX, textY);

            //         // Adding 'Submmited' text
            //         var secondLabelFontSize = (height / 300).toFixed(
            //         2); // Smaller font size
            //         // var secondLabelFontSize = RFS - 1;
            //         ctx.font = "bold " + secondLabelFontSize + "em figtree";
            //         var secondLabelText = "Submitted",
            //             secondLabelTextX = Math.round((width - ctx.measureText(
            //                 secondLabelText).width) / 2),
            //             secondLabelTextY = textY - (BASE_FONT_SIZE *
            //             2.5); // Position above the percentage text
            //         ctx.fillText(secondLabelText, secondLabelTextX, secondLabelTextY);
            //         ctx.save();
            //     }
            // }


        ]
    });

    setTimeout(() => {
        /* https://www.chartjs.org/docs/latest/developers/api.html#update-mode */
        console.log(myChartCircle.options.plugins.annotation.annotations.labelData.content[0]);
        myChartCircle.options.plugins.annotation.annotations.labelData.content[0] = 'Updated';
        myChartCircle.options.animation = false;
        myChartCircle.update('show');
    }, 3000);
});

document.addEventListener('livewire:init', () => {
    //

});


document.addEventListener("DOMContentLoaded", (event) => {

});



