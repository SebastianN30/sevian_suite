<template>
    <Head title="Crear Usuario" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Crear usuario
            </h2>
        </template>
        <div v-if="showErrors"
            class="mb-4 p-2 bg-red-100 dark:bg-red-900 border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 transition-colors duration-300">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-lg dark:text-white">Errores encontrados</h3>
                <button @click="clearErrors"
                    class="ml-2 text-4xl font-bold rounded-full p-2 hover:bg-red-200 dark:hover:bg-red-700 transition">
                    &times;
                </button>
            </div>

            <ul class="list-disc list-inside mt-2 space-y-1">
                <li v-for="(error, key) in $page.props.errors" :key="key" class="dark:text-red-300">
                    {{ error }}
                </li>
            </ul>
        </div>
        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 dark:bg-gray-800 border border-gray-200 shadow sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-white ">Nombre</label>
                            <input v-model="form.name" type="text" id="name" 
                                name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Nombre" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-white ">Email</label>
                            <input v-model="form.email" type="email" id="email" 
                                name="email" class="mt-1 p-2 border rounded-md w-full" placeholder="Email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-white ">Contraseña</label>
                            <input v-model="form.password" type="password" id="password" 
                                name="password" class="mt-1 p-2 border rounded-md w-full" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-white ">Confirmar contraseña</label>
                            <input v-model="form.password_confirmation" type="password" id="password_confirmation" 
                                name="password" class="mt-1 p-2 border rounded-md w-full" placeholder="Confirmar contraseña" required>
                        </div>
                        <div class="flex space-x-4">
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Crear usuario</button>
                            <button type="button" @click="back" class="bg-red-500 text-white py-2 px-4 rounded">Regresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const page = usePage();
const props = defineProps({

});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
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

function submit(e){
    e.preventDefault();
    form.post(route('user.store'), {
        onError: () => {
            showErrors.value = true;
        }
    });
}

function back() {
    router.visit(route('user.index'));
}
</script>