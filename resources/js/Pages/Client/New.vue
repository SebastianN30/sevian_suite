<template>
    <Head title="Crear cliente" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Crear cliente
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
                        <form action="">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Nombre</label>
                                <input type="text" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Nombre" required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Cedula</label>
                                <input type="number" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Cedula" required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Telefono</label>
                                <input type="number" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Telefono" required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Correo</label>
                                <input type="number" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Correo" required>
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Crear</button>
                                <button type="button" @click="back" class="bg-red-500 text-white py-2 px-4 rounded">Regresar</button>
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
import Paginator from '@/Pages/Pagination/Paginator.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

function back(){
    router.visit(route('client.index'));
}
</script>