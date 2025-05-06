window.renderScoreMapChart = function (scoreMapData, canvasId = 'scoreMapChart') {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    const scatterData = {
        datasets: [{
            label: 'スコアマップ',
            data: scoreMapData.map(item => ({
                x: item.change ?? 0,
                y: item.score ?? 0,
                question_id: item.question_id,
                questionText: item.questionText,
            })),
            pointRadius: 6,
            backgroundColor: '#AFB2B5',
            parsing: false,
        }]
    };

    const config = {
        type: 'scatter',
        data: scatterData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: '#FF8D80',
                    displayColors: false,
                    callbacks: {
                        label: function (context) {
                            const point = context.raw;
                            return `${point.questionText}`;
                        }
                    }
                },
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            },
            scales: {
                x: {
                    min: -50,
                    max: 50,
                    ticks: {
                        display: true,
                        stepSize: 25
                    },
                    grid: {
                        drawTicks: false
                    },
                    title: {
                        display: true,
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        display: true,
                        stepSize: 25
                    },
                    grid: {
                        drawTicks: false
                    },
                    title: {
                        display: true,
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
};