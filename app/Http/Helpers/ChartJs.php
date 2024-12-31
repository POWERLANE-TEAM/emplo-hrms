<?php

namespace App\Http\Helpers;

class ChartJs
{
    public static function pieGenerateLabels()
    {
        return 'beforeInit: (CHART) => {
                            CHART.data.datasets.forEach((dataset) => {
                                const total = dataset.data.reduce((acc, val) => acc + val,
                                    0);
                                dataset.data = dataset.data.map((data, index) => {
                                    if (index === dataset.data.length - 1) {
                                        return 100 - total + data;
                                    }
                                    return data;
                                });
                            }); // Calculate remaining percentage
                        }';
    }


    public static function pieGenerateBgColors(array $valueVars, int $maxvalue,  array $colorVars, string $defaultColorVar)
    {

        $valueChecks = implode(' || ', array_map(function ($var) {
            return "$var == maxValue";
        }, $valueVars));

        // ChatGPT did this there is no way I did this myself
        // Not supported public links yet for convo with images https://chatgpt.com/c/6766de66-b54c-8001-984d-c5e1c469a6b6
        return 'function(context) {
            const CHART = context.chart;
            const {
                ctx,
                chartArea
            } = CHART;

            // Check if chartArea and element are ready
            if (!CHART.chartArea || !context.element)
                return  '  . $defaultColorVar . '; // Fallback color


            // Retrieve slice-specific data
            const {
                startAngle,
                endAngle,
                innerRadius,
                outerRadius
            } = context.element;

            // Ensure these variables are valid
            if (
                !isFinite(startAngle) ||
                !isFinite(endAngle) ||
                !isFinite(innerRadius) ||
                !isFinite(outerRadius)
            ) {
                return  '  . $defaultColorVar . '; // Fallback color
            }

            // Calculate chart center
            const xCenter = CHART.chartArea.width / 2 + CHART.chartArea.left;
            const yCenter = CHART.chartArea.height / 2 + CHART.chartArea.top;

            // Calculate slice-specific coordinates
            const xStart = xCenter + Math.cos(startAngle) * outerRadius;
            const yStart = yCenter + Math.sin(startAngle) * outerRadius;

            const xEnd = xCenter + Math.cos(endAngle) * outerRadius;
            const yEnd = yCenter + Math.sin(endAngle) * outerRadius;

            // Ensure coordinates are finite
            if (
                !isFinite(xStart) ||
                !isFinite(yStart) ||
                !isFinite(xEnd) ||
                !isFinite(yEnd)
            ) {
                console.warn(\'Invalid gradient coordinates:\', {
                    xStart,
                    yStart,
                    xEnd,
                    yEnd,
                });
                return  '  . $defaultColorVar . '; // Fallback color
            }

            // Create gradient
            const GRADIENT = ctx.createLinearGradient(xStart, yStart, xEnd, yEnd);

            // Assign colors
            const colors = [ ' . implode(', ', $colorVars) . ',  '  . $defaultColorVar . '];
            const color = colors[context.dataIndex] ||  '  . $defaultColorVar . '; // Fallback color


            if ([' . implode(', ', $valueVars) . '].includes(' . $maxvalue . ')) {
                return colors[context.dataIndex];
            }

            // handle no submission
            if (( ' . implode(' + ', $valueVars) . ') == 0) {
                return colors[context.dataIndex];
            }

            GRADIENT.addColorStop(0, color);
            GRADIENT.addColorStop(1, blendColors(color, "#F5F5F5", 0.55));

            return GRADIENT;
        }';
    }
}
