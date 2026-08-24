<!-- src/components/LineChart.vue -->
<template>
  <Line :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { computed } from 'vue'
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)

const props = defineProps({
  labels: { type: Array, required: true },
  likes: { type: Array, required: true },
  views: { type: Array, required: true },
})

const formattedLabels = computed(() =>
  props.labels.map((d) => {
    const date = new Date(d)
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
  })
)

const chartData = computed(() => ({
  labels: formattedLabels.value,
  datasets: [
    {
      label: 'Likes',
      data: props.likes,
      borderColor: '#C9A56A',
      backgroundColor: 'rgba(201, 165, 106, 0.1)',
      tension: 0.3,
      fill: true,
      pointRadius: 3,
    },
    {
      label: 'Views',
      data: props.views,
      borderColor: '#808080',
      backgroundColor: 'rgba(128, 128, 128, 0.08)',
      tension: 0.3,
      fill: true,
      pointRadius: 3,
    },
  ],
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: { mode: 'index', intersect: false },
  plugins: {
    legend: {
      display: true,
      position: 'top',
      align: 'end',
      labels: { color: 'rgba(26,26,26,0.7)', boxWidth: 12, font: { size: 11 } },
    },
  },
  scales: {
    x: {
      ticks: { color: 'rgba(26,26,26,0.5)', font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 },
      grid: { display: false },
    },
    y: {
      ticks: { color: 'rgba(26,26,26,0.5)' },
      grid: { color: 'rgba(210,196,180,0.3)' },
      beginAtZero: true,
    },
  },
}
</script>