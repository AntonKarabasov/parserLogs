import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

const data = {
    labels: requestsMostPopularBrowsersPerDays[0]['dates'],
    datasets: [
        {
        label: requestsMostPopularBrowsersPerDays[0]['name'],
        data: requestsMostPopularBrowsersPerDays[0]['percent'],
        fill: false,
        borderColor: 'rgb(192,75,79)',
        tension: 0.1
        },
        {
            label: requestsMostPopularBrowsersPerDays[1]['name'],
            data: requestsMostPopularBrowsersPerDays[1]['percent'],
            fill: false,
            borderColor: 'rgb(75,98,192)',
            tension: 0.1
        },
        {
            label: requestsMostPopularBrowsersPerDays[2]['name'],
            data: requestsMostPopularBrowsersPerDays[2]['percent'],
            fill: false,
            borderColor: 'rgb(75,192,85)',
            tension: 0.1
        }
    ]
};

const config = {
    type: 'line',
    data: data,
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Три самых популярных браузера'
            }
        }
    }
};

const chartRequestsPerDays = new Chart(
    document.getElementById('requests_most_popular_browsers'),
    config
);