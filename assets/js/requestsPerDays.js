import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

let datesChartRequestPerDays = [];
let requestsChartRequestPerDays = [];

for (let value of requestsPerDays) {
    datesChartRequestPerDays.push(value.day);
    requestsChartRequestPerDays.push(value.requests)
}

const data = {
    labels: datesChartRequestPerDays,
    datasets: [{
        label: 'Число запросов',
        data: requestsChartRequestPerDays,
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }]
};

const config = {
    type: 'line',
    data: data,
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Запросы по дням'
            }
        }
    }
};

const chartRequestsPerDays = new Chart(
    document.getElementById('requests_per_days'),
    config
);