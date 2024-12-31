// Imports
import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";

function initializeCharts() {
    // ================================
    // Utils
    // ================================
    const Utils = {
        CHART_COLORS: {
            blue: "rgba(0, 98, 144, 0.39)",
            green: "rgb(179, 217, 137)",
        },
    };

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

    document.querySelectorAll("canvas").forEach((canvas) => {
        canvas.parentElement.style.position = "relative";
        canvas.parentElement.style.height = "50%";
        canvas.parentElement.style.width = "100%";
    });

    // ================================
    // Charts Main Script
    // ================================
    const chartConfigs = [
        {
            id: "overtime-chart",
            type: "doughnut",
            data: {
                labels: ["Utilized", "Remaining"],
                datasets: [{
                    data: [75, 25],
                    backgroundColor: [Utils.CHART_COLORS.blue, Utils.CHART_COLORS.green],
                }],
            },
        },
        {
            id: "attendance-chart",
            type: "bar",
            data: {
                labels: ["Attendance Compliance Rate", "Unplanned Absence Rate"],
                datasets: [{
                    label: "Attendance",
                    data: [92, 8],
                    backgroundColor: [Utils.CHART_COLORS.blue, Utils.CHART_COLORS.green],
                }],
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
                datasets: [{
                    label: "Resolution Time (days)",
                    data: [3.0, 2.8, 2.5, 2.2],
                    borderColor: Utils.CHART_COLORS.blue,
                    fill: false,
                }],
            },
            extraOptions: {
                scales: { y: { beginAtZero: true } },
            },
        },
    ];

    // Initialize general charts
    chartConfigs.forEach((chartConfig) => {
        const ctx = document.getElementById(chartConfig.id)?.getContext("2d");
        if (ctx) {
            new Chart(ctx, {
                type: chartConfig.type,
                data: chartConfig.data,
                options: {
                    ...commonOptions,
                    ...(chartConfig.extraOptions || {}),
                },
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", initializeCharts);

export { initializeCharts };