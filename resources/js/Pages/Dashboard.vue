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
                        <div class="w-full">
                            <div class="mb-4">
                                <label for="year" class="block text-sm font-medium text-gray-200 mb-2">Seleccionar Año:</label>
                                <select 
                                    id="year" 
                                    v-model="selectedYear"
                                    @change="updateChart"
                                    class="bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                >
                                    <option v-for="year in availableYears" :key="year" :value="year">
                                        {{ year }}
                                    </option>
                                </select>
                            </div>
                            <Line :data="lineChartData" :options="lineChartOptions" />
                        </div>
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
import { Pie, Line } from 'vue-chartjs';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale, PointElement, LineElement)

const props = defineProps({
    counts: Object,
    topsProducts: Object,
    monthlySales: Object,
    selectedYear: Number
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

// Configuración del gráfico de líneas
const selectedYear = ref(props.selectedYear || new Date().getFullYear());

const availableYears = computed(() => {
    const currentYear = new Date().getFullYear();
    return Array.from({length: 5}, (_, i) => currentYear - i);
});

const lineChartData = computed(() => ({
    labels: props.monthlySales?.months || [],
    datasets: [
        {
            label: `Ventas ${selectedYear.value}`,
            data: props.monthlySales?.totals || [],
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.4,
            fill: true
        }
    ]
}));

const lineChartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                color: '#ffffff'
            }
        },
        title: {
            display: true,
            text: 'Ventas Mensuales',
            color: '#ffffff'
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(255, 255, 255, 0.1)'
            },
            ticks: {
                color: '#ffffff'
            }
        },
        x: {
            grid: {
                color: 'rgba(255, 255, 255, 0.1)'
            },
            ticks: {
                color: '#ffffff'
            }
        }
    }
};

const updateChart = () => {
    router.get(route('dashboard'), { year: selectedYear.value }, {
        preserveState: true,
        preserveScroll: true,
        only: ['monthlySales', 'selectedYear']
    });
};
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