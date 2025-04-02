<template>

    <Head title="Crear cliente" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Crear cliente
            </h2>
        </template>

        <div v-if="showErrors"
            class="mb-4 p-4 border rounded-lg bg-red-100 border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-300 transition-colors duration-300">

            <div class="flex justify-between items-center">
                <h3 class="font-bold text-lg">Errores encontrados</h3>
                <button @click="clearErrors"
                    class="ml-2 text-4xl font-bold rounded-full p-2 hover:bg-red-200 dark:hover:bg-red-700 transition">
                    &times;
                </button>
            </div>

            <ul class="list-disc list-inside mt-2 space-y-1">
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
                                Crear Cliente
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
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const page = usePage();
const form = useForm({
    name: '',
    lastname: '',
    email: '',
    phone_number: '',
    address: ''
});

const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);

watch(errors, (newErrors) => {
    showErrors.value = Object.keys(newErrors).length > 0;
});

function clearErrors() {
    showErrors.value = false;
    form.clearErrors();
}

function submit() {
    form.post(route('client.store'), {
        onError: () => {
            showErrors.value = true;
        }
    });
}

function back() {
    router.visit(route('client.index'));
}
</script>
