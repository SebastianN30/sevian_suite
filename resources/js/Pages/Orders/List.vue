<template>

    <Head title="Pedidos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Ordenes
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

        <div class="py-12">
            <div
                class="max-w-7xl mx-auto px-6 sm:px-8 space-y-6 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg bg-white dark:bg-gray-800">

                <div class="p-6 flex justify-between items-center border-b border-gray-300 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Lista de Pedidos</h3>
                </div>

                <div class="p-6 flex justify-between items-center">
                    <input v-model="search"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-4 w-1/4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        type="text" placeholder="Buscar Ordenes...">
                </div>

                <div class="p-6">
                    <table class="min-w-full mt-5 border-collapse w-full rounded-xl overflow-hidden shadow-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                    Cliente</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                    Productos</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                    Total de venta</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="orders.data.length === 0">
                                <td class="px-6 py-4 text-center text-gray-800 dark:text-gray-300" colspan="4">No hay
                                    información disponible</td>
                            </tr>
                            <tr v-for="order in orders.data" :key="order.id"
                                class="border-b border-gray-300 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300">
                                    <a :href="route('order.edit', order.id)"
                                        class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-500 underline hover:underline-offset-2 transition-colors duration-200">{{
                                            order.id }}</a>
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300"><a
                                        :href="route('client.show', order.client.id)"
                                        class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-500 underline hover:underline-offset-2 transition-colors duration-200">{{
                                            order.client.name + ' ' +
                                            order.client.lastname }}</a></td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300">{{ order.products.length }}</td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300">{{ formatCurrency(order.total) }}
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300"><span :class="{
                                    'bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200': order.status === 'creada',
                                    'bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-200': order.status === 'pendiente',
                                    'bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-200': order.status === 'completada'
                                }" class="px-4 py-2 rounded-full text-center font-semibold">
                                        {{ order.status }}
                                    </span></td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <Link :href="route('order.edit', order.id)"
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors duration-200 shadow-sm flex items-center">
                                        <Eye class="w-5 h-5" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center w-full mt-6">
                        <div class="text-gray-800 dark:text-gray-300 text-sm">Mostrando {{ orders.from }} a {{
                            orders.to }} de
                            un total de {{ orders.total }} registros</div>
                        <Paginator :links="orders.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Paginator from '@/Pages/Pagination/Paginator.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Swal from 'sweetalert2';
import { Eye, Trash, FileBadge2 } from 'lucide-vue-next';

const page = usePage();
const props = defineProps({
    orders: Object,
    filters: Object
});

const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);
const search = ref(props.filters.search || '');
const flashSuccess = ref(page.props.flash?.success || null);

watch(search, (value) => {
    router.get(route('order.index'), { search: value }, { preserveState: true, replace: true });
});

watch(errors, (newErrors) => {
    showErrors.value = Object.keys(newErrors).length > 0;
});

function clearErrors() {
    showErrors.value = false;
    flashSuccess.value = null;
}

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
}
</script>
