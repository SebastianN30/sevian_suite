<template>
    <Head title="Crear orden" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Crear orden
            </h2>
            <div class="py-12">
                <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 dark:bg-gray-800 border border-gray-200 shadow sm:rounded-lg">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Producto</label>
                                <input v-model="form.product_name" type="text" id="product_name" 
                                    name="product_name" class="mt-1 p-2 border rounded-md w-full" placeholder="Producto" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white ">Cantidad</label>
                                <input v-model="form.product_quantity" type="number" id="product_quantity" 
                                    name="product_quantity" class="mt-1 p-2 border rounded-md w-full" placeholder="Cantidad" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white ">Opcion de pago</label>
                                <input v-model="form.option_payment" type="number" id="option_payment" 
                                    name="option_payment" class="mt-1 p-2 border rounded-md w-full" placeholder="Opcion de pago" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white ">Medoto de pago</label>
                                <input v-model="form.method_payment" type="number" id="method_payment" 
                                    name="method_payment" class="mt-1 p-2 border rounded-md w-full" placeholder="Medoto de pago" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white ">Cliente</label>
                                <input v-model="form.client" type="text" id="client" 
                                    name="client" class="mt-1 p-2 border rounded-md w-full" placeholder="Cliente" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white ">Comentario</label>
                                <input v-model="form.note" type="text" id="note" 
                                    name="note" class="mt-1 p-2 border rounded-md w-full" placeholder="Comentario" required>
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
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const form = useForm({
    product_quantity: '',
    product_quantity: '',
    client: '',
    note: ''
});

function submit(){
    form.post(route('order.store'));
}

function back(){
    router.visit(route('order.index'));
}
</script>