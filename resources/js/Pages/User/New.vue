<template>
    <Head title="Crear Usuario" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Crear usuario
            </h2>
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
                                    name="email" class="mt-1 p-2 border rounded-md w-full" placeholder="Apellido" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-white ">Contraseña</label>
                                <input v-model="form.password" type="password" id="password" 
                                    name="password" class="mt-1 p-2 border rounded-md w-full" placeholder="Contraseña" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-white ">Confirmar contraseña</label>
                                <input v-model="form.password_confirmation" type="password" id="password" 
                                    name="password" class="mt-1 p-2 border rounded-md w-full" placeholder="Confirmar contraseña" required>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Crear usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({

});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

function submit(){
    form.post(route('user.store'));
}
</script>