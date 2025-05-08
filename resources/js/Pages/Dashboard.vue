<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-8 space-y-6 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
            <div class="flex justify-center p-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Ordenes
                </h2>
            </div>
            <div>
                <div class="flex justify-center">
                    <div class="data-tabs">
                        <div class="data-tab">
                            <div class="number">{{ counts.complete }}</div>
                            <p class="title">Completadas</p>
                        </div>
                        <div class="data-tab">
                            <div class="number">{{ counts.pending }}</div>
                            <p class="title">Pendientes</p>
                        </div>
                        <div class="data-tab">
                            <div class="number">{{ counts.create }}</div>
                            <p class="title">Creadas</p>
                        </div>
                        <div class="data-tab">
                            <div class="number">{{ counts.cancel }}</div>
                            <p class="title">Canceladas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-2 px-6">
                <div class="flex flex-wrap md:flex-nowrap gap-4 justify-center">
                    <div class="w-full md:w-1/2 max-w-lg mx-auto bg-white dark:bg-gray-900 rounded-xl p-4">
                        <h3 class="text-lg font-bold text-center text-white mb-2">Top 5 Productos mas vendidos</h3>
                        <Pie :data="chartData" :options="chartOptions" />
                    </div>
                    <div class="w-full md:w-1/2 max-w-lg mx-auto bg-white dark:bg-gray-900 rounded-xl p-4 shadow flex items-center justify-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Aquí irá otro gráfico</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Pie } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const props = defineProps({
    counts: Object,
    topsProducts: Object
});

const chartData = {
  labels: props.topsProducts.map(p => p.name),
  datasets: [{
    label: 'Productos más vendidos',
    data: props.topsProducts.map(p => p.total_vendido),
    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
  }]
}

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        color: '#ffffff',
      }
    },
    tooltip: {
      bodyColor: '#ffffff',
      titleColor: '#FFCE56'
    }
  }
}

</script>

<style scoped>
    .data-tabs {
    display: flex;
    width: 165%;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
    }

    .data-tab {
    flex: 1;
    min-width: 150px;
    padding: 20px;
    background-color: #ececec;
    border-radius: 8px;
    text-align: center;
    }

    .number {
    font-size: 2.5rem;
    color: #1A202C;
    font-weight: bold;
    }

    .title {
    margin-top: 10px;
    font-size: 1rem;
    color: #4A5568;
    }
</style>