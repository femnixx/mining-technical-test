<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

// We register these Chart.js modules once so they are available in the component
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    // The data arriving from your DashboardController (e.g., model_name and total)
    chartData: Array 
});

// We map the array of objects into the separate arrays that Chart.js expects
const data = {
    labels: props.chartData.map(item => item.model_name),
    datasets: [{
        label: 'Total Approved Usage',
        backgroundColor: '#4f46e5', 
        borderColor: '#3730a3', 
        borderWidth: 1,
        data: props.chartData.map(item => item.total)
    }]
};

const options = {
    responsive: true,
    maintainAspectRatio: false, 
    scales: {
        y: {
            beginAtZero: true, 
            ticks: { stepSize: 1 } 
        }
    }
};
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow border border-gray-100 h-96">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Fleet Utilization</h3>
        <div class="h-80">
            <Bar :data="data" :options="options" />
        </div>
    </div>
</template>