<template>
    <Head title="Stock" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Clientes
            </h2>
            <div>
                <Link :href="route('customer.create')"
                    class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition-colors duration-200">
                    Crear cliente
                </Link>
            </div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                        <table class="min-w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cedula
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Telefono
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Correo
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                <!-- <tr v-if="!users">
                                    <td class="px-6 py-4 whitespace-no-wrap text-center text-gray-700 dark:text-gray-300" colspan="3">
                                        No hay información disponible
                                    </td>
                                </tr> -->
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700"></td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700"></td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700"></td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700"></td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-center items-center space-x-4">
                                            <Link :href="route('customer.edit')"
                                                class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-500 text-white py-2 px-4 rounded transition-colors duration-200">
                                            Editar</Link>
                                            <Link :href="route('customer.delete')"
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
</script>