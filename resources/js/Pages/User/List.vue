<template>
    <Head title="Usuarios" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Usuarios
            </h2>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                        <table class="min-w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                <tr v-if="!users">
                                    <td class="px-6 py-4 whitespace-no-wrap text-center text-gray-700 dark:text-gray-300" colspan="3">
                                        No hay información disponible
                                    </td>
                                </tr>
                                <tr v-for="user in users" :key="user.id" class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300">{{ user.name }}</td>
                                    <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300">{{ user.email }}</td>
                                    <td class="px-4 py-2 whitespace-no-wrap">
                                        <Link :href="route('user.edit', user.id)"
                                            class="mt-4 inline-block bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition-colors duration-200">
                                        Editar usuario</Link>
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
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    users: Object
});
console.log(props.users);
</script>