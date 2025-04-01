<template>

    <Head title="Clientes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Clientes
            </h2>
        </template>

        <div class="py-12">
            <div
                class="max-w-7xl mx-auto px-6 sm:px-8 space-y-6 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg bg-gray-800">

                <div class="p-6 flex justify-between items-center border-b border-gray-700">
                    <h3 class="text-xl font-bold text-white">Lista de Clientes</h3>
                </div>

                <div class="p-6 flex justify-between items-center">
                    <input v-model="search" @input="filterClients"
                        class="bg-gray-700 text-white border border-gray-600 rounded-lg py-2 px-4 w-1/4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        type="text" placeholder="Buscar clientes...">

                    <Link :href="route('client.create')"
                        class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors duration-200 shadow-sm">
                    Crear cliente
                    </Link>
                </div>


                <div class="p-6">
                    <table class="min-w-full mt-5 border-collapse w-full rounded-xl overflow-hidden shadow-sm">
                        <thead>
                            <tr class="bg-gray-700">
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                    Nombres
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                    Correo
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                    Teléfono</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredClients.length === 0">
                                <td class="px-6 py-4 text-center text-gray-300" colspan="4">
                                    No hay información disponible
                                </td>
                            </tr>
                            <tr v-for="client in filteredClients" :key="client.id"
                                class="border-b border-gray-700 hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 text-gray-300">
                                    <a :href="route('client.edit', client.id)"
                                        class="text-blue-400 hover:text-blue-500 underline hover:underline-offset-2 transition-colors duration-200">
                                        {{ client.name + ' ' + client.lastname }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ client.email }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ client.phone_number }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <Link :href="route('client.delete', client.id)" method="delete"
                                            class="bg-red-600 hover:bg-red-500 text-white py-1 px-3 rounded-lg transition-colors duration-200 shadow-sm">
                                        Eliminar
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-center w-full mt-6">
                        <Paginator :links="clients.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Paginator from '@/Pages/Pagination/Paginator.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    'clients': Object
});

const search = ref('');

const filteredClients = computed(() => {
    if (!search.value.trim()) {
        return props.clients.data;
    }
    const searchTerm = search.value.toLowerCase();
    return props.clients.data.filter(client =>
        (client.name.toLowerCase().includes(searchTerm) || client.lastname.toLowerCase().includes(searchTerm) || client.email.toLowerCase().includes(searchTerm))
    );
});

const filterClients = () => { };
</script>
