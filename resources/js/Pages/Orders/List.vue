<template>
    <Head title="Ordenes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Ordenes
            </h2>
            <div>
                <Link :href="route('order.create')"
                    class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition-colors duration-200">
                    Crear orden
                </Link>
            </div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                        <table class="min-w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Id
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Estatus
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                <tr v-if="!orders.data">
                                    <td class="px-6 py-4 whitespace-no-wrap text-center text-gray-700 dark:text-gray-300" colspan="3">
                                        No hay información disponible
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-700" v-for="order in orders.data" :key="order.id">
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        {{ order.id }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        {{ order.client_id }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        {{ order.status }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        {{ order.total }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        {{ order.updated_at }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-center items-center space-x-4">
                                            <Link :href="route('order.edit')"
                                                class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-500 text-white py-2 px-4 rounded transition-colors duration-200">
                                            Editar</Link>
                                            <Link :href="route('order.delete')"
                                                method="delete"
                                                class="mt-4 inline-block bg-red-600 hover:bg-red-500 text-white py-2 px-4 rounded transition-colors duration-200"
                                                >
                                            Eliminar</Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Paginator from '@/Pages/Pagination/Paginator.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    'orders': Object
});
</script>