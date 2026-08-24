<!-- src/components/BarChart.vue -->
<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { computed } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  labels: { type: Array, required: true },
  values: { type: Array, required: true },
  label: { type: String, default: 'Total' },
  color: { type: String, default: '#C9A56A' },
})

const truncatedLabels = computed(() =>
  props.labels.map((l) => (l.length > 14 ? l.slice(0, 14) + '…' : l))
)

const chartData = computed(() => ({
  labels: truncatedLabels.value,
  datasets: [
    {
      label: props.label,
      data: props.values,
      backgroundColor: props.color,
      borderRadius: 6,
    },
  ],
}))

const chartOptions = {
  indexAxis: 'y',
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    tooltip: {
      callbacks: {
        title: (items) => props.labels[items[0].dataIndex],
      },
    },
  },
  scales: {
    x: {
      ticks: { color: 'rgba(26,26,26,0.6)' },
      grid: { color: 'rgba(210,196,180,0.3)' },
      beginAtZero: true,
    },
    y: {
      ticks: { color: 'rgba(26,26,26,0.75)', font: { size: 11 } },
      grid: { display: false },
    },
  },
}
</script>