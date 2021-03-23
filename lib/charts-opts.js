/**
 * Default settings for Charts.js
 * @link https://www.chartjs.org/docs/
 */

// TODO: In the longer term find a better way to get the color values in here
// TODO: In the meantime, you will need to update these vars to match your project

var fontStackBody = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'";
var fontStackTitles = fontStackBody;

var colorBorders = [
  'rgba(255, 99, 132, 1)',
  'rgba(54, 162, 235, 1)',
  'rgba(255, 206, 86, 1)',
  'rgba(75, 192, 192, 1)',
  'rgba(153, 102, 255, 1)',
  'rgba(255, 159, 64, 1)'
];

var colorFills = [
  'rgba(255, 99, 132, 0.4)',
  'rgba(54, 162, 235, 0.4)',
  'rgba(255, 206, 86, 0.4)',
  'rgba(75, 192, 192, 0.4)',
  'rgba(153, 102, 255, 0.4)',
  'rgba(255, 159, 64, 0.4)'
];

Chart.defaults.global.maintainAspectRatio = false; // Allows charts to be responsive and always fill their container

// Base Fonts
// @link https://www.chartjs.org/docs/latest/general/fonts.html
Chart.defaults.global.defaultFontColor = 'hsl(209, 20%, 16%)';
Chart.defaults.global.defaultFontFamily = fontStackBody;
Chart.defaults.global.defaultFontSize = 16; // Has to be px - no em allowed here

// Titles
// @link https://www.chartjs.org/docs/latest/configuration/title.html
Chart.defaults.global.title.fontFamily = fontStackTitles;
Chart.defaults.global.title.fontSize = 20; // Has to be px - no em allowed here
Chart.defaults.global.title.padding = 16;
Chart.defaults.global.title.lineHeight = 1.6;

// Tooltips
// @link https://www.chartjs.org/docs/latest/configuration/tooltip.html
Chart.defaults.global.tooltips.backgroundColor = 'rgba(0, 0, 0, 0.9)';

// Animations
// @link https://www.chartjs.org/docs/latest/configuration/animations.html
Chart.defaults.global.animation.duration = 2000;

// The chart legend displays data about the datasets that are appearing on the chart.
// @link https://www.chartjs.org/docs/latest/configuration/legend.html?h=legend
Chart.defaults.global.legend.display = false;
Chart.defaults.global.legend.labels.boxWidth = 15;
Chart.defaults.global.legend.labels.fontSize = 14;
Chart.defaults.global.legend.labels.padding = 8;

// Rectangle elements are used to represent the bars in a bar chart.
// @link https://www.chartjs.org/docs/latest/configuration/elements.html#rectangle-configuration
Chart.defaults.global.elements.rectangle.borderWidth = 2;
Chart.defaults.global.elements.rectangle.backgroundColor = colorFills;
Chart.defaults.global.elements.rectangle.borderColor = colorBorders;

// Arcs are used in the polar area, doughnut and pie charts.
// @link https://www.chartjs.org/docs/latest/configuration/elements.html#arc-configuration
Chart.defaults.global.elements.arc.borderWidth = 2;
Chart.defaults.global.elements.arc.backgroundColor = colorFills;
Chart.defaults.global.elements.arc.borderColor = colorBorders;






// Line elements are used to represent the line in a line chart.
// @link https://www.chartjs.org/docs/latest/configuration/elements.html#line-configuration
Chart.defaults.global.elements.line.backgroundColor = 'rgba(57,181,74,0.5)';
Chart.defaults.global.elements.line.borderColor = 'rgba(57,181,74,0.8)';

// Point elements are used to represent the points in a line, radar or bubble chart.
// @link https://www.chartjs.org/docs/latest/configuration/elements.html#point-configuration
Chart.defaults.global.elements.point.backgroundColor = colorFills;
Chart.defaults.global.elements.point.radius = 5;
Chart.defaults.global.elements.point.hoverRadius = 8;
Chart.defaults.global.elements.point.backgroundColor = '#39b54a';
Chart.defaults.global.elements.point.borderColor = '#39b54a';

// Settings for Charts-JS Deferred. Prevents charts from animating until they are in the viewport
// @link https://github.com/chartjs/chartjs-plugin-deferred - Defer animation until in viewport
Chart.defaults.global.plugins.deferred.xOffset = 150; // defer until 150px of the canvas width is inside the viewport
Chart.defaults.global.plugins.deferred.yOffset = '50%'; // defer until 50% of the canvas height is inside the viewport
Chart.defaults.global.plugins.deferred.delay = 500; // delay of 500 ms after the canvas is considered inside the viewport
