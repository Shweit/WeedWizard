import annotationPlugin from 'chartjs-plugin-annotation';

document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(annotationPlugin);
});