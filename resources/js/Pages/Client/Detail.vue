<template>

    <Head title="Detalle del Cliente" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 mb-4">
                Detalle del Cliente - {{ client.name + ' ' + client.lastname }}
            </h2>
        </template>

        <div v-if="flashSuccess"
            class="mb-4 p-4 border rounded-lg text-green-700 bg-green-100 border-green-400 dark:bg-green-900 dark:border-green-700 dark:text-green-200 transition-colors flex justify-between items-center">
            <span>{{ flashSuccess }}</span>
            <button @click="clearErrors"
                class="ml-2 text-2xl font-bold rounded-full p-1 hover:bg-red-200 dark:hover:bg-red-700 transition text-red-600 dark:text-red-400">
                &times;
            </button>
        </div>

        <div v-if="showErrors"
            class="mb-4 p-4 border rounded-lg bg-red-100 border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-300 transition-colors duration-300">

            <div class="flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">Errores encontrados</h3>
                <button @click="clearErrors"
                    class="ml-2 text-4xl font-bold rounded-full p-2 hover:bg-red-200 dark:hover:bg-red-700 transition">
                    &times;
                </button>
            </div>

            <ul class="list-disc list-inside mt-2 space-y-1">
                <li v-for="(error, key) in $page.props.errors" :key="key" class="text-gray-800 dark:text-gray-200">
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg space-y-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Información del Cliente</h3>
                    <div class="space-y-2">
                        <p class="text-gray-600 dark:text-gray-400"><strong>Correo:</strong> {{ client.email }}</p>
                        <p class="text-gray-600 dark:text-gray-400"><strong>Teléfono:</strong> {{ client.phone_number }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400"><strong>Dirección:</strong> {{ client.address }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Ganancia Total (Órdenes
                            Completadas)</h3>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                            ${{ totalProfit.toLocaleString('es-ES') }}
                        </p>
                    </div>
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Promedio de Ganancia por producto
                        </h3>
                        <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">
                            ${{ averageProfit.toLocaleString('es-ES') }}
                        </p>
                    </div>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Top de Productos Comprados
                        (Órdenes
                        Completadas)</h3>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Producto</th>
                                <th class="px-6 py-3">Cantidad Total</th>
                                <th class="px-6 py-3">Total Gastado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in topProducts" :key="product.name"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ product.name }}</td>
                                <td class="px-6 py-4">{{ product.total_quantity }}</td>
                                <td class="px-6 py-4">${{ product.total_spent.toLocaleString('es-ES') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Gráfica de Productos Más
                        Comprados (Órdenes Completadas)</h3>
                    <div class="h-96">
                        <canvas id="productChart"></canvas>
                    </div>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Gráfica de Productos Más
                        Comprados (Órdenes Completadas)</h3>
                    <div class="h-96">
                        <canvas id="productPieChart"></canvas>
                    </div>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Ganancias por Orden</h3>
                    <div class="h-96">
                        <canvas id="profitChart"></canvas>
                    </div>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Órdenes</h3>
                    <div v-if="client.orders.length > 0" class="space-y-6">
                        <div v-for="order in client.orders" :key="order.id"
                            class="p-4 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg transition-all duration-300 transform hover:scale-105 cursor-pointer"
                            @click="toggleOrder(order.id)">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Orden #{{ order.id }}</p>
                                    <p class="font-bold text-gray-800 dark:text-gray-200">Total: ${{
                                        order.total.toLocaleString('es-ES') }}</p>
                                    <p class="text-sm" :class="{
                                        'text-green-600 dark:text-green-400': order.status === 'completada',
                                        'text-red-600 dark:text-red-400': order.status === 'cancelada',
                                        'text-yellow-600 dark:text-yellow-400': order.status === 'pendiente',
                                        'text-blue-600 dark:text-blue-400': order.status === 'creada'
                                    }">
                                        Estado: {{ order.status }}
                                    </p>
                                </div>
                                <div>
                                    <button class="text-sm text-blue-500 dark:text-blue-300 underline">
                                        {{ expandedOrder === order.id ? 'Ocultar detalles' : 'Ver detalles' }}
                                    </button>
                                </div>
                            </div>

                            <div v-if="expandedOrder === order.id" class="mt-4 space-y-2">
                                <p class="font-bold text-gray-700 dark:text-gray-300">Productos:</p>
                                <ul class="space-y-1">
                                    <li v-for="product in order.products" :key="product.id"
                                        class="ml-4 text-gray-600 dark:text-gray-400">
                                        <b>{{ product.name }}</b> - Cantidad: {{ product.pivot.quantity }} - Precio
                                        Unitario:
                                        ${{
                                            product.pivot.price.toLocaleString('es-ES') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-gray-600 dark:text-gray-400">Este cliente aún no tiene órdenes.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch, computed } from 'vue';
import Chart from 'chart.js/auto';

const page = usePage();
const { props } = usePage();
const totalProfit = props.totalProfit;
const averageProfit = props.averageProfit;
const client = props.client;
const topProducts = props.topProducts;
const expandedOrder = ref(null);
const flashSuccess = ref(page.props.flash?.success || null);
const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);

function toggleOrder(orderId) {
    expandedOrder.value = expandedOrder.value === orderId ? null : orderId;
}

watch(errors, (newErrors) => {
    showErrors.value = Object.keys(newErrors).length > 0;
});

function clearErrors() {
    showErrors.value = false;
    flashSuccess.value = null;
}

onMounted(() => {
    const ctxBar = document.getElementById('productChart').getContext('2d');
    const ctxPie = document.getElementById('productPieChart').getContext('2d');
    const ctxProfit = document.getElementById('profitChart').getContext('2d');

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: topProducts.map(product => product.name),
            datasets: [{
                label: 'Cantidad Comprada',
                data: topProducts.map(product => product.total_quantity),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: topProducts.map(product => product.name),
            datasets: [{
                data: topProducts.map(product => product.total_quantity),
                backgroundColor: [
                    '#FF6384', // Rosa suave
                    '#36A2EB', // Azul suave
                    '#FFCE56', // Amarillo suave
                    '#4BC0C0', // Verde agua
                    '#9966FF', // Morado suave
                    '#FF9F40', // Naranja suave
                    '#66FF66', // Verde claro
                    '#FF6666', // Rojo claro
                    '#66B2FF', // Azul claro
                    '#FFB266'  // Melocotón suave
                ],
                borderColor: '#1F2937', // Color oscuro para mejor contraste
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    new Chart(ctxProfit, {
        type: 'bar',
        data: {
            labels: client.orders
                .filter(order => order.status === 'completada')
                .map(order => `Orden #${order.id}`),
            datasets: [{
                label: 'Ganancia por Orden',
                data: client.orders
                    .filter(order => order.status === 'completada')
                    .map(order => order.products.reduce((acc, product) =>
                        acc + (product.pivot.price - product.pivot.internal_price) * product.pivot.quantity, 0)),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
