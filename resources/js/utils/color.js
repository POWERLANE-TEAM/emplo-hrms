
window.validateTextColor = function (color) {
    const s = new Option().style;
    s.color = color;
    return s.color !== '';
}

export default function createGradient(ctx, chartArea, color, start = 0, percent = 0.55) {
    // const GRADIENT = ctx.createLinearGradient(start, 0, 0, chartArea.width);

    console.log(chartArea.top);
    console.log(chartArea.bottom);
    const GRADIENT = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
    GRADIENT.addColorStop(0, color);
    // GRADIENT.addColorStop(0.9, color);
    GRADIENT.addColorStop(1, blendColors(color, '#F5F5F5', percent));
    return GRADIENT;
}

window.createGradient = createGradient;

export function blendColors(color1, color2, percentage) {
    const hex = (color) => {
        const hex = color.replace('#', '');
        return {
            r: parseInt(hex.substring(0, 2), 16),
            g: parseInt(hex.substring(2, 4), 16),
            b: parseInt(hex.substring(4, 6), 16)
        };
    };

    const color1RGB = hex(color1);
    const color2RGB = hex(color2);

    const blendedColor = {
        r: Math.round(color1RGB.r * (1 - percentage) + color2RGB.r * percentage),
        g: Math.round(color1RGB.g * (1 - percentage) + color2RGB.g * percentage),
        b: Math.round(color1RGB.b * (1 - percentage) + color2RGB.b * percentage)
    };

    return `#${((1 << 24) + (blendedColor.r << 16) + (blendedColor.g << 8) + blendedColor.b).toString(16).slice(1)}`;
}

window.blendColors = blendColors;
