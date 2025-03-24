document.addEventListener('DOMContentLoaded', function() {
    // Configuration des requêtes API
    const apiBaseUrl = 'http://localhost:8000/api';

    // Éléments canvas
    const ctxProgress = document.getElementById('progressChart');
    const ctxCompletion = document.getElementById('completionChart');
    const ctxStreak = document.getElementById('streakChart');

    // Couleurs des datasets
    const chartColors = {
        primary: 'rgba(75, 192, 192, 0.6)',
        secondary: 'rgba(255, 159, 64, 0.6)',
        tertiary: 'rgba(153, 102, 255, 0.6)'
    };

    // Détruire les anciennes instances de graphiques
    let progressChart = null;
    let completionChart = null;
    let streakChart = null;

    // Récupération des données
    fetch(`${apiBaseUrl}/habits/stats`)
        .then(response => response.json())
        .then(data => {
            // Données par défaut si vide
            const dates = data.dates || [];
            const values = data.values || [];
            const habits = data.habits || ['Aucune donnée'];
            const completions = data.completions || [0];
            const streaks = data.streaks || [0];

            // Destruction des anciens graphiques
            if (progressChart) progressChart.destroy();
            if (completionChart) completionChart.destroy();
            if (streakChart) streakChart.destroy();

            // Graphique de progression globale
            progressChart = new Chart(ctxProgress, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Progression des habitudes',
                        data: values,
                        borderColor: chartColors.primary,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Progression quotidienne' }
                    },
                    scales: {
                        y: {
                            title: { display: true, text: '% de l\'objectif' },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique de taux de complétion
            completionChart = new Chart(ctxCompletion, {
                type: 'bar',
                data: {
                    labels: habits,
                    datasets: [{
                        label: 'Taux de complétion',
                        data: completions,
                        backgroundColor: chartColors.secondary
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Taux de réussite' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: '% de complétion' }
                        }
                    }
                }
            });

            // Graphique de séquence
            streakChart = new Chart(ctxStreak, {
                type: 'doughnut',
                data: {
                    labels: ['Séquence actuelle', 'Maximum atteint'],
                    datasets: [{
                        data: streaks,
                        backgroundColor: [chartColors.tertiary, '#e0e0e0']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Séquences' }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Erreur de récupération des données:', error);
        });
});