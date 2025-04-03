<template>
    <Head title="Editar producto" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Editar producto
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
                                <label for="image" class="block text-sm font-medium text-white mb-2">Imagen del producto</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="image-upload" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Haz clic para subir</span> o arrastra y suelta
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG o JPEG (MAX. 2MB)</p>
                                        </div>
                                        <input 
                                            id="image-upload" 
                                            type="file" 
                                            accept="image/*"
                                            class="hidden"
                                            @change="handleImageUpload"
                                        />
                                    </label>
                                </div>
                                <!-- Vista previa de la imagen -->
                                <div v-if="imagePreview" class="mt-4">
                                    <img :src="imagePreview" class="h-32 w-32 object-cover rounded-lg" />
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Nombre</label>
                                <input type="text" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Nombre" required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Precio base</label>
                                <input type="number" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Precio base" required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Porcentaje de ganancia</label>
                                <input type="number" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Porcentaje de ganancia" required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white ">Cantidad</label>
                                <input type="number" id="name" 
                                    name="name" class="mt-1 p-2 border rounded-md w-full" placeholder="Cantidad" required>
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Actualizar</button>
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

const imagePreview = ref(null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Crear URL temporal para la vista previa
        imagePreview.value = URL.createObjectURL(file);
        
        // Aquí puedes manejar la subida del archivo
        // Por ejemplo, agregarlo a un FormData para enviarlo al servidor
    }
};

function back(){
    router.visit(route('product.index'));
}
</script>