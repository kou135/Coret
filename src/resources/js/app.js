import './bootstrap';

import Chart from 'chart.js/auto';
window.Chart = Chart;

import './charts/totalScoreChart';
import './charts/itemScoreChart';
import './charts/scoreMapChart';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
