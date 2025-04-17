<template>
    <Head title="Productos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Productos
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 space-y-6 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
                <div class="p-6 flex justify-between items-center border-b border-gray-300 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Lista de Productos</h3>
                </div>
                <div class="p-6 flex justify-between items-center">
                    <input v-model="search"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-4 w-1/4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        type="text" placeholder="Buscar productos...">
                    <Link :href="route('product.create')"
                        class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition-colors duration-200">
                        Crear producto
                    </Link>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                    <table class="min-w-full mt-5">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Imagen
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Precio base
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Precio PVP
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Cantidad
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            <tr v-if="!products.data">
                                <td class="px-6 py-4 whitespace-no-wrap text-center text-gray-700 dark:text-gray-300" colspan="3">
                                    No hay información disponible
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700" v-for="product in products.data" :key="product.id">
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    <img :src="product.image" 
                                    :alt="product.name"
                                    class="w-20 h-20 object-cover rounded">
                                </td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    {{ product.name }}
                                </td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    {{ product.internal_price }}
                                </td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    {{ product.sale_price }}
                                </td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    {{ product.stock }}
                                </td>
                                <td class="px-4 py-2 whitespace-no-wrap text-gray-700 dark:text-gray-300 border-x border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-center items-center space-x-4">
                                        <button
                                            @click="openModal(product)"
                                            class="mt-4 inline-block bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded transition-colors duration-200"
                                            >
                                            <PackageOpen />
                                        </button>
                                        <Link :href="route('product.edit', product.id)"
                                            class="mt-4 inline-block bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded transition-colors duration-200">
                                            <SquarePen/>
                                        </Link>
                                        <Link :href="route('product.delete')"
                                            method="delete"
                                            class="mt-4 inline-block bg-red-600 hover:bg-red-500 text-white py-2 px-4 rounded transition-colors duration-200"
                                            >
                                            <Trash/>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <Modal 
                            :isOpen="isModalOpen" 
                            :product="selectedProduct" 
                            @close="closeModal" 
                        />
                    </table>
                    <div class="flex justify-center w-full mt-4">
                        <Paginator :links="products.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PackageOpen,SquarePen,Trash  } from 'lucide-vue-next';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Paginator from '@/Pages/Pagination/Paginator.vue';
import Modal from '@/Pages/Modals/Stock.vue';
import { ref, watch, computed } from 'vue';

const page = usePage();
const props = defineProps({
    products: Object,
    filters: Object
});

const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);
const search = ref(props.filters.search || '');
const flashSuccess = ref(page.props.flash?.success || null);

watch(search, (value) => {
    router.get(route('product.index'), { search: value }, { preserveState: true, replace: true });
});

watch(errors, (newErrors) => {
    showErrors.value = Object.keys(newErrors).length > 0;
});

const isModalOpen = ref(false);
const selectedProduct = ref(false);

const openModal = (product) => {
    /* console.log("Abriendo modal con producto:", product); */
    selectedProduct.value = product;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value =null
};
</script>