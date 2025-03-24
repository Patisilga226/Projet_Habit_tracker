document.addEventListener('DOMContentLoaded', function() {
    // Éléments canvas
    const monthlyTrendCanvas = document.getElementById('monthlyTrendChart');
    const typeDistributionCanvas = document.getElementById('typeDistributionChart');

    // Récupérer les données depuis les attributs data
    const monthlyData = JSON.parse(monthlyTrendCanvas.dataset.monthlyData || '[]');
    const typeDistribution = JSON.parse(typeDistributionCanvas.dataset.typeDistribution || '{}');
    const dailyGoal = parseInt(monthlyTrendCanvas.dataset.dailyGoal || '0');

    // Graphique de tendance mensuelle
    new Chart(monthlyTrendCanvas, {
        type: 'bar',
        data: {
            labels: monthlyData.map(data => data.month),
            datasets: [{
                label: 'Consommation moyenne (ml)',
                data: monthlyData.map(data => data.average),
                backgroundColor: monthlyData.map(data => 
                    data.average >= dailyGoal ? 'rgba(34, 197, 94, 0.6)' : 'rgba(59, 130, 246, 0.6)'
                ),
                borderColor: monthlyData.map(data => 
                    data.average >= dailyGoal ? 'rgb(34, 197, 94)' : 'rgb(59, 130, 246)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const percentage = Math.round((value / dailyGoal) * 100);
                            return `${value} ml (${percentage}% de l'objectif)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Consommation (ml)'
                    }
                }
            }
        }
    });

    // Graphique de distribution par type
    new Chart(typeDistributionCanvas, {
        type: 'doughnut',
        data: {
            labels: Object.keys(typeDistribution).map(type => {
                const labels = {
                    water: 'Eau',
                    tea: 'Thé',
                    coffee: 'Café',
                    juice: 'Jus',
                    other: 'Autre'
                };
                return labels[type] || type;
            }),
            datasets: [{
                data: Object.values(typeDistribution).map(data => data.percentage),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.6)',  // blue
                    'rgba(34, 197, 94, 0.6)',   // green
                    'rgba(234, 179, 8, 0.6)',   // yellow
                    'rgba(249, 115, 22, 0.6)',  // orange
                    'rgba(107, 114, 128, 0.6)'  // gray
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(234, 179, 8)',
                    'rgb(249, 115, 22)',
                    'rgb(107, 114, 128)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            return `${value}% du total`;
                        }
                    }
                }
            }
        }
    });
}));