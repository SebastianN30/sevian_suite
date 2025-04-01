<template>

    <Head title="Editar cliente" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ client.name + ' ' + client.lastname }}
            </h2>
        </template>
        <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0"
            class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                <li v-for="(error, key) in $page.props.errors" :key="key">
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="py-8">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-6 bg-gray-800 border border-gray-700 shadow sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-white mb-1">Nombre</label>
                            <input v-model="form.name" type="text" id="name" name="name"
                                class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                placeholder="Nombre" required>
                        </div>

                        <div class="mb-4">
                            <label for="lastname" class="block text-sm font-medium text-white mb-1">Apellido</label>
                            <input v-model="form.lastname" type="text" id="lastname" name="lastname"
                                class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                placeholder="Apellido" required>
                        </div>

                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-white mb-1">Teléfono</label>
                            <input v-model="form.phone_number" type="text" id="phone_number" name="phone_number"
                                class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                placeholder="Teléfono" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-white mb-1">Correo</label>
                            <input v-model="form.email" type="email" id="email" name="email"
                                class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                placeholder="Correo" required>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-white mb-1">Dirección</label>
                            <textarea v-model="form.address" id="address" name="address"
                                class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                placeholder="Dirección" rows="4"></textarea>
                        </div>

                        <div class="flex justify-between mt-6 space-x-4">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Guardar Cambios
                            </button>
                            <button type="button" @click="back"
                                class="bg-red-600 hover:bg-red-700 text-white py-2 px-6 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                Regresar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    'client': Object
});

const form = useForm({
    id: props.client.id || '',
    name: props.client.name || '',
    lastname: props.client.lastname || '',
    email: props.client.email || '',
    phone_number: props.client.phone_number || '',
    address: props.client.address || ''
});

function submit() {
    form.post(route('client.update'));
}


function back() {
    router.visit(route('client.index'));
}
</script>
