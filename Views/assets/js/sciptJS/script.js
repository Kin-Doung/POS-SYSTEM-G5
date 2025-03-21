document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [
                {
                    label: 'This week',
                    data: [7000, 8000, 7500, 7800, 7700, 7300, 7200],
                    backgroundColor: '#3b82f6'
                },
                {
                    label: 'Last week',
                    data: [9000, 9500, 9400, 9600, 9700, 9500, 9400],
                    backgroundColor: '#facc15'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
