<template>

    <Head title="Nuevo Pedido" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">Nuevo Pedido</h2>
        </template>

        <div v-if="flashSuccess"
            class="mb-4 p-4 border rounded-lg text-green-700 bg-green-100 border-green-400 dark:bg-green-900 dark:border-green-700 dark:text-green-200 transition-colors flex justify-between items-center">
            <span>{{ flashSuccess }}</span>
            <button @click="clearErrors"
                class="ml-2 text-2xl font-bold rounded-full p-1 hover:bg-red-200 dark:hover:bg-red-700 transition text-red-600 dark:text-red-400">
                &times;
            </button>
        </div>

        <div v-if="showErrors"
            class="mb-4 p-4 border rounded-lg bg-red-100 border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-300 transition-colors duration-300">

            <div class="flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">Errores encontrados</h3>
                <button @click="clearErrors"
                    class="ml-2 text-4xl font-bold rounded-full p-2 hover:bg-red-200 dark:hover:bg-red-700 transition">
                    &times;
                </button>
            </div>

            <ul class="list-disc list-inside mt-2 space-y-1">
                <li v-for="(error, key) in $page.props.errors" :key="key" class="text-gray-800 dark:text-gray-200">
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div
                    class="p-6 bg-white dark:bg-[#1e293b] shadow-md sm:rounded-2xl transition-all border border-gray-200 dark:border-gray-700">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Cliente: {{ client.name }} {{
                            client.lastname }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ client.email }} - {{ client.phone_number }}</p>
                    </div>

                    <!-- Tabla de productos seleccionados -->
                    <div class="overflow-x-auto rounded-lg mt-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-[#334155] text-gray-700 dark:text-gray-300">
                                    <th class="px-4 py-3">Producto</th>
                                    <th class="px-4 py-3">Cantidad</th>
                                    <th class="px-4 py-3">Stock Disponible</th>
                                    <th class="px-4 py-3">Precio</th>
                                    <th class="px-4 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in selectedProducts" :key="index"
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-[#1e293b] dark:even:bg-[#263140] transition">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ product.name }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        <input type="number" v-model.number="product.quantity" :max="product.stock"
                                            @input="validateQuantity(product)"
                                            class="w-20 p-1 border rounded dark:bg-[#2b3646] text-gray-900 dark:text-gray-100" />
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ product.stock }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{
                                        formatCurrency(product.sale_price)
                                        }}</td>
                                    <td class="px-4 py-3">
                                        <button @click="removeProduct(index)"
                                            class="bg-red-500 hover:bg-red-400 text-white py-1 px-3 rounded-lg transition">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Buscador de productos -->
                    <div class="mt-6">
                        <label for="productSearch"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Buscar
                            Producto</label>
                        <input type="text" v-model="searchQuery"
                            class="w-full p-2 mb-4 border rounded-lg bg-gray-100 dark:bg-[#2b3646] text-gray-900 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="Buscar por nombre de producto..." />
                    </div>

                    <!-- Resumen -->
                    <div class="mt-6 p-4 border-t dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Total:</h3>
                            <span class="text-xl font-bold text-blue-500">{{ formatCurrency(total) }}</span>
                        </div>
                        <button @click="submitOrder"
                            class="mt-4 bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded-lg transition-all">Crear
                            Pedido</button>
                    </div>

                    <div class="overflow-x-auto rounded-lg mt-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-[#334155] text-gray-700 dark:text-gray-300">
                                    <th class="px-4 py-3">Imagen</th>
                                    <th class="px-4 py-3">Producto</th>
                                    <th class="px-4 py-3">Cantidad Actual</th>
                                    <th class="px-4 py-3">Precio</th>
                                    <th class="px-4 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in filteredProducts" :key="index"
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-[#1e293b] dark:even:bg-[#263140] transition">
                                    <td class="px-4 py-3">
                                        <img :src="product.image" alt="Imagen del producto"
                                            class="w-16 h-16 object-cover rounded-lg">
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ product.name }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ product.stock }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{
                                        formatCurrency(product.sale_price)
                                    }}</td>
                                    <td class="px-4 py-3">
                                        <button @click="addProductToList(product)"
                                            class="bg-green-500 hover:bg-green-400 text-white py-1 px-3 rounded-lg transition">Añadir</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const page = usePage();
const client = page.props.client;
const products = page.props.products;

const selectedProductId = ref('');
const selectedProducts = ref([]);
const total = ref(0);

const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);
const flashSuccess = ref(page.props.flash?.success || null);

const searchQuery = ref('');

const filteredProducts = computed(() => {
    if (!searchQuery.value.trim()) {
        return products;
    }
    return products.filter(product =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

function addProductToList(product) {
    const existingProduct = selectedProducts.value.find(p => p.id === product.id);

    if (existingProduct) {
        if (existingProduct.quantity < product.stock) {
            existingProduct.quantity++;
            calculateTotal();
        } else {
            alert('No puedes agregar más de la cantidad disponible en stock.');
        }
    } else {
        if (product.stock > 0) {
            selectedProducts.value.push({
                ...product,
                quantity: 1
            });
            calculateTotal();
        } else {
            alert('No hay stock disponible para este producto.');
        }
    }
}

watch(errors, (newErrors) => {
    showErrors.value = Object.keys(newErrors).length > 0;
});

function clearErrors() {
    showErrors.value = false;
    flashSuccess.value = null;
}

function removeProduct(index) {
    selectedProducts.value.splice(index, 1);
    calculateTotal();
}

function calculateTotal() {
    total.value = selectedProducts.value.reduce((sum, product) => {
        return sum + (product.sale_price * product.quantity);
    }, 0);
}

function submitOrder() {
    router.post(route('client.store.order', client.id), {
        products: selectedProducts.value
    });
}

function formatCurrency(value) {
    return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
}

function validateQuantity(product) {
    if (product.quantity > product.stock) {
        alert('No puedes ingresar más cantidad que el stock disponible.');
        product.quantity = product.stock;
    } else if (product.quantity < 1) {
        alert('La cantidad mínima es 1.');
        product.quantity = 1;
    }
    calculateTotal();
}

</script>
