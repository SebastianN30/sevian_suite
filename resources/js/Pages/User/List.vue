<template>
    <Head title="Usuarios" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Usuarios
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 space-y-6 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
                <div class="p-6 flex justify-between items-center border-b border-gray-300 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Lista de Usuarios</h3>
                </div>
                <div class="p-6 flex justify-between items-center">
                    <input
                        v-model="search"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-4 w-1/4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        type="text" placeholder="Buscar usuarios...">
                    <Link :href="route('user.create')"
                        class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition-colors duration-200">
                        Crear usuario
                    </Link>
                </div>
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
                            <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">{{ user.name }}</td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">{{ user.email }}</td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-center items-center space-x-4">
                                        <Link :href="route('user.edit', user.id)"
                                            class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-500 text-white py-2 px-4 rounded transition-colors duration-200">
                                        Editar usuario</Link>
                                        <Link :href="route('user.delete', user.id)"
                                            method="delete"
                                            class="mt-4 inline-block bg-red-600 hover:bg-red-500 text-white py-2 px-4 rounded transition-colors duration-200"
                                            >
                                        Eliminar usuario</Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center w-full mt-4">
                        <Paginator :links="users.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Paginator from '@/Pages/Pagination/Paginator.vue';

const page = usePage();
const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    }
});

const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);
const search = ref(props.filters.search || '');
const flashSuccess = ref(page.props.flash?.success || null);

watch(search, (value) => {
    router.get(route('user.index'), { search: value }, { preserveState: true, replace: true });
});

watch(errors, (newErrors) => {
    showErrors.value = Object.keys(newErrors).length > 0;
});

function clearErrors() {
    showErrors.value = false;
    flashSuccess.value = null;
}
</script>