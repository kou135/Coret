window.renderTotalScoreChart = function(totalBeforeAfterData, canvasId = 'totalScoreChart') {
    const ctx = document.getElementById(canvasId);
    if (!ctx || !totalBeforeAfterData || totalBeforeAfterData.length === 0) return;

    const latest = totalBeforeAfterData[totalBeforeAfterData.length - 1];
    const latestScore = latest.after ?? 0;

    const config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [latestScore, 100 - latestScore],
                backgroundColor: [
                    'rgba(20, 184, 166, 0.8)',  // teal-500
                    'rgba(229, 231, 235, 0.5)'  // gray-200
                ],
                borderWidth: 0,
                cutout: '65%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            }
        }
    };

    new Chart(ctx, config);
};
