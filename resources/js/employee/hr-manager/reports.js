// Imports
import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";

function initializeCharts() {
    const Utils = {
        CHART_COLORS: {
            blue: "rgba(0, 98, 144, 0.39)",
            green: "rgb(179, 217, 137)",
        },
    };

    const leaveData = {
        all: { utilized: [85, 10342], remaining: [15, 324] }, 
        sick: { utilized: [60, 900], remaining: [40, 600] },
        vacation: { utilized: [90, 1350], remaining: [10, 150] },
        paternity: { utilized: [70, 1050], remaining: [30, 450] },
        maternity: { utilized: [50, 750], remaining: [50, 750] },
    };

    const initialLeaveType = 'all';
    const initialLeaveData = leaveData[initialLeaveType];

    // Initialize the chart variable here to make it globally accessible
    let leaveUtilizationChart;

    document.querySelectorAll("canvas").forEach((canvas) => {
        canvas.parentElement.style.position = "relative";
        canvas.parentElement.style.height = "50%";
        canvas.parentElement.style.width = "100%";
    });

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: "top",
            },
        },
    };

    const chartConfigs = [
        {
            id: "overtime-chart",
            type: "doughnut",
            data: {
                labels: ["Utilized", "Remaining"],
                datasets: [
                    {
                        data: [75, 25],
                        backgroundColor: [
                            Utils.CHART_COLORS.blue,
                            Utils.CHART_COLORS.green,
                        ],
                    },
                ],
            },
        },
        {
            id: "attendance-chart",
            type: "bar",
            data: {
                labels: [
                    "Attendance Compliance Rate",
                    "Unplanned Absence Rate",
                ],
                datasets: [
                    {
                        label: "Attendance",
                        data: [92, 8],
                        backgroundColor: [
                            Utils.CHART_COLORS.blue,
                            Utils.CHART_COLORS.green,
                        ],
                    },
                ],
            },
            extraOptions: {
                scales: {
                    x: { stacked: false },
                    y: { beginAtZero: true, max: 100 },
                },
            },
        },
        {
            id: "issue-resolution-chart",
            type: "line",
            data: {
                labels: ["January", "February", "March", "April"],
                datasets: [
                    {
                        label: "Resolution Time (days)",
                        data: [3.0, 2.8, 2.5, 2.2],
                        borderColor: Utils.CHART_COLORS.blue,
                        fill: false,
                    },
                ],
            },
            extraOptions: {
                scales: { y: { beginAtZero: true } },
            },
        },
        {
            id: "leave-utilization-chart",
            type: "pie",
            data: {
                labels: ["Utilized Leave", "Remaining"],
                datasets: [
                    {
                        data: [initialLeaveData.utilized[0], initialLeaveData.remaining[0]],
                        backgroundColor: [
                            Utils.CHART_COLORS.blue,
                            Utils.CHART_COLORS.green,
                        ],
                    },
                ],
            },
            extraOptions: {
                plugins: {
                    legend: {
                        display: true,
                        position: "top",
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const leaveType = document.getElementById("leave-type-dropdown").value; // Get selected leave type
                                const isUtilized = tooltipItem.label === "Utilized Leave";
                                
                                // Extract the relevant data from leaveData based on the selected leave type
                                const leaveDataForType = leaveData[leaveType];
                                const [percentage, actualDays] = isUtilized 
                                    ? leaveDataForType.utilized 
                                    : leaveDataForType.remaining;
                                
                                return `${tooltipItem.label}: ${percentage}% (${actualDays} days)`;
                            },
                        },
                    },
                },
            },
        },
    ];

    // Loop through chartConfigs and initialize charts
    chartConfigs.forEach((chartConfig) => {
        const ctx = document.getElementById(chartConfig.id)?.getContext("2d");
        if (ctx) {
            if (chartConfig.id === "leave-utilization-chart") {
                leaveUtilizationChart = new Chart(ctx, {
                    type: chartConfig.type,
                    data: chartConfig.data,
                    options: {
                        ...commonOptions,
                        ...(chartConfig.extraOptions || {}),
                    },
                });
            } else {
                new Chart(ctx, {
                    type: chartConfig.type,
                    data: chartConfig.data,
                    options: {
                        ...commonOptions,
                        ...(chartConfig.extraOptions || {}),
                    },
                });
            }
        }
    });

    // Event listener for dropdown change
    document.getElementById("leave-type-dropdown").addEventListener("change", function (event) {
        const selectedType = event.target.value;

        // Make sure the leaveUtilizationChart is initialized before updating
        if (leaveUtilizationChart) {
            const selectedLeaveData = leaveData[selectedType];
    
            // Update chart data based on the selected leave type
            leaveUtilizationChart.data.datasets[0].data = [
                selectedLeaveData.utilized[0],  // Utilized percentage
                selectedLeaveData.remaining[0],  // Remaining percentage
            ];
    
            // Update the chart
            leaveUtilizationChart.update();
        }
    });
}

document.addEventListener("DOMContentLoaded", initializeCharts);

export { initializeCharts };
