<template>
    <Head title="Editar usuario" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Editar Usuario
            </h2>
            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" 
                class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    <li v-for="(error, key) in $page.props.errors" :key="key">
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
                                <label class="block text-sm font-medium text-white mb-2">¿Deseas actualizar la contraseña?</label>
                                <select v-model="updatePassword" class="mt-1 p-2 border rounded-md w-full bg-white text-gray-900">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                            <div v-if="updatePassword === '1'" class="mb-4">
                                <label class="block text-sm font-medium text-white mb-2">Contraseña</label>
                                <input v-model="form.password" type="password" 
                                    name="password" class="mt-1 p-2 border rounded-md w-full" placeholder="Nueva contraseña">
                            </div>
                            <div v-if="updatePassword === '1'" class="mb-4">
                                <label class="block text-sm font-medium text-white mb-2">Confirmar contraseña</label>
                                <input v-model="form.password_confirmation" type="password" 
                                    name="password" class="mt-1 p-2 border rounded-md w-full" placeholder="Confirma contraseña">
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Actualizar usuario</button>
                                <button @click="back" type="button" class="bg-red-500 text-white py-2 px-4 rounded">Regresar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object,
    errors: Object
});

const updatePassword = ref('0');

const form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: ''
});

const submit = () => {
    if(updatePassword.value === '0'){
        form.password = '';
        form.password_confirmation = '';
    }
    
    form.post(route('user.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.password = '';
            form.password_confirmation = '';
            updatePassword.value = '0';
        }
    });
};

function back(){
    router.visit(route('user.index'));
}
</script>