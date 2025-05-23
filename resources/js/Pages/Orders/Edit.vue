<template>

    <Head :title="client.name + ' ' + client.lastname" />

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

        <div v-if="flashError"
            class="mb-4 p-4 border rounded-lg text-red-700 bg-red-100 border-red-400 dark:bg-red-900 dark:border-red-700 dark:text-red-300 transition-colors flex justify-between items-center">
            <span>{{ flashError }}</span>
            <button @click="clearFlashError"
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
                    <div class="mb-6 p-4 rounded-lg text-center font-bold text-xl" :class="{
                        'bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-200': order.status === 'completada',
                        'bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200': order.status === 'creada',
                        'bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-200': order.status === 'pendiente',
                        'bg-orange-200 text-orange-800 dark:bg-orange-700 dark:text-orange-200': order.status === 'pago_parcial'
                    }">
                        ESTADO DE LA ORDEN: {{ order.status == 'pago_parcial' ? 'PAGANDO' : order.status.toUpperCase()
                        }}
                    </div>

                    <div class="mt-6 text-center" v-if="['creada', 'pendiente', 'pago_parcial'].includes(order.status)">
                        <button v-if="order.status === 'creada'" @click="changeStatus('pendiente')"
                            class="bg-yellow-500 hover:bg-yellow-400 text-white py-2 px-4 rounded-lg mr-4 transition">
                            Pasar a Pendiente
                        </button>

                        <button @click="deleteOrder"
                            class="bg-red-500 hover:bg-red-400 text-white py-2 px-4 rounded-lg transition">
                            Eliminar Orden
                        </button>

                        <button v-if="order.status === 'pendiente'" @click="changeStatus('pago_parcial')"
                            class="bg-orange-500 hover:bg-orange-400 text-white py-2 px-4 rounded-lg ml-4 transition">
                            Pagos a Cuotas
                        </button>

                        <button v-if="order.status === 'pendiente' || order.status === 'pago_parcial'"
                            @click="changeStatus('completada')"
                            class="bg-green-500 hover:bg-green-400 text-white py-2 px-4 rounded-lg ml-4 transition">
                            Pasar a Completada
                        </button>
                    </div>


                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Cliente: {{ client.name }} {{
                            client.lastname }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ client.email }} - {{ client.phone_number }}</p>
                    </div>

                    <!-- FORMULARIO para definir cuotas -->
                    <div v-if="!has_installments && order.status === 'pago_parcial'" class="mt-6">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Definir Cuotas</h4>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad de
                                cuotas:</label>
                            <input type="number" min="1" v-model.number="installmentsCount"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-white">
                        </div>

                        <div v-for="(installment, index) in installments" :key="index"
                            class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto de la
                                    cuota {{
                                        index + 1 }}:</label>
                                <input type="number" min="0" v-model.number="installments[index].amount"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de
                                    vencimiento:</label>
                                <input type="date" v-model="installments[index].due_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-white">
                            </div>
                        </div>

                        <button @click="submitInstallments"
                            class="bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded-lg transition mt-2">
                            Guardar Cuotas
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-lg mt-6"
                        v-if="has_installments && (order.status == 'pago_parcial' || order.status == 'completada')">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Cuotas</h4>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-[#334155] text-gray-700 dark:text-gray-300">
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Monto</th>
                                    <th class="px-4 py-3">Vencimiento</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3" v-if="order.status !== 'completada'">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(installment, i) in order.installments" :key="i"
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-[#1e293b] dark:even:bg-[#263140] transition">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ i + 1 }}</td>

                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        <input v-if="installment.status !== 'pagada'" type="number"
                                            v-model="installment.amount"
                                            class="w-24 px-2 py-1 rounded border text-sm dark:bg-gray-700 dark:text-white">
                                        <span v-else>{{ formatCurrency(installment.amount) }}</span>
                                    </td>

                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        <input v-if="installment.status !== 'pagada'" type="date"
                                            :value="formatDate(installment.due_date)"
                                            @input="updateDueDate(i, $event.target.value)"
                                            class="w-36 px-2 py-1 rounded border text-sm dark:bg-gray-700 dark:text-white">
                                        <span v-else>{{ formatDate(installment.due_date) }}</span>
                                    </td>

                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        <template v-if="installment.status === 'pendiente'">
                                            <select v-model="installment.status"
                                                class="px-2 py-1 rounded border text-sm dark:bg-gray-700 dark:text-white">
                                                <option value="pendiente">Pendiente</option>
                                                <option value="pagada">Pagado</option>
                                            </select>
                                        </template>
                                        <span v-else :class="{
                                            'text-green-500': installment.status === 'pagada',
                                            'text-yellow-500': installment.status === 'pendiente',
                                            'text-red-500': installment.status === 'vencida'
                                        }">
                                            {{ installment.status === 'pagada' ? 'Pagada' : (installment.status ===
                                                'pendiente' ?
                                                'Pendiente' : 'Vencida') }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100" v-if="order.status !== 'completada'">
                                        <button @click="saveChanges(i)" v-if="order.status !== 'completada'"
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-2 py-1 rounded">
                                            Guardar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <!-- Tabla de productos seleccionados -->
                    <div class="overflow-x-auto rounded-lg mt-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-[#334155] text-gray-700 dark:text-gray-300">
                                    <th class="px-4 py-3">Producto</th>
                                    <th class="px-4 py-3">Cantidad</th>
                                    <th class="px-4 py-3" v-if="order.status === 'creada'">Stock Disponible</th>
                                    <th class="px-4 py-3">Precio</th>
                                    <th class="px-4 py-3" v-if="order.status === 'creada'">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in selectedProducts" :key="index"
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-[#1e293b] dark:even:bg-[#263140] transition">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100"><a
                                            :href="route('product.edit', product.id)"
                                            class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-500 underline hover:underline-offset-2 transition-colors duration-200">{{
                                                product.name }}</a></td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                        v-if="order.status === 'creada'">
                                        <input type="number" v-model.number="product.pivot.quantity"
                                            :max="product.stock + (originalOrder.products[index]?.pivot.quantity ?? 0)"
                                            @input="validateQuantity(product, index)"
                                            class="w-20 p-1 border rounded dark:bg-[#2b3646] text-gray-900 dark:text-gray-100" />
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100" v-else>{{
                                        product.pivot.quantity }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                        v-if="order.status === 'creada'">{{
                                            product.stock }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{
                                        formatCurrency(product.sale_price)
                                    }}
                                        <template v-if="product.pivot.change && order.status == 'creada'">
                                            <br>
                                            <small
                                                :class="{ 'text-green-500': product.pivot.change > 0, 'text-red-500': product.pivot.change < 0 }"
                                                class="text-xs">
                                                Cambio: {{ product.pivot.change > 0 ? '+' : '' }}{{
                                                    formatCurrency(product.pivot.change) }}
                                            </small>
                                        </template>
                                    </td>
                                    <td class="px-4 py-3" v-if="order.status === 'creada'">
                                        <button @click="removeProduct(index)"
                                            class="bg-red-500 hover:bg-red-400 text-white py-1 px-3 rounded-lg transition">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Buscador de productos -->
                    <div class="mt-6" v-if="order.status === 'creada'">
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
                            class="mt-4 bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded-lg transition-all"
                            v-if="order.status === 'creada'">Editar
                            Pedido</button>
                    </div>

                    <div class="overflow-x-auto rounded-lg mt-6" v-if="order.status === 'creada'">
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
import { ref, computed, watch, onMounted } from 'vue';
import Swal from 'sweetalert2';

const installmentsCount = ref(0)
const installments = ref([])

const page = usePage();
const client = page.props.client;
const products = page.props.products;
const order = page.props.order;
const originalOrder = JSON.parse(JSON.stringify(order));
const has_installments = page.props.has_installments;

const selectedProductId = ref('');
const selectedProducts = ref(order.products);
const total = ref(order.total);

const errors = computed(() => page.props.value?.errors ?? {});
const showErrors = ref(false);
const flashSuccess = ref(page.props.flash?.success || null);
const flashError = ref(page.props.flash?.error || null);

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
        const productIndex = selectedProducts.value.findIndex(p => p.id === product.id);
        if (existingProduct.pivot.quantity < (originalOrder.products[productIndex].pivot.quantity + product.stock)) {
            existingProduct.pivot.quantity++;
            calculateTotal();
        } else {
            alert('No puedes agregar más de la cantidad disponible en stock.');
        }
    } else {
        if (product.stock > 0) {
            selectedProducts.value.push({
                ...product,
                pivot: {
                    quantity: 1,
                    new: true
                }
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

watch(() => page.props.flash?.success, (newFlash) => {
    if (newFlash) {
        flashSuccess.value = newFlash;
    }
});

watch(installmentsCount, (newVal) => {
    installments.value = Array.from({ length: newVal }, () => ({
        amount: 0,
        due_date: ''
    }))
})

function isValid() {
    return installments.value.every(inst => inst.amount > 0 && inst.due_date)
}

function submitInstallments() {
    if (!isValid()) {
        showErrors.value = true
        return
    }

    router.post(route('orders.installments.store'), {
        order_id: order.id,
        installments: installments.value
    }, {
        onError: () => {
            showErrors.value = true;
        },
        onSuccess: () => {
            const errorMessage = page.props.flash?.error;
            if (errorMessage) {
                localStorage.setItem('flashError', errorMessage);
            }

            const successMessage = page.props.flash?.success;
            if (successMessage) {
                localStorage.setItem('flashSuccess', successMessage);
            }
            router.visit(route('order.edit', order.id))
        }
    })
}

function clearErrors() {
    showErrors.value = false;
    flashSuccess.value = null;
}


function clearFlashError() {
    flashError.value = null;
}

function removeProduct(index) {
    selectedProducts.value.splice(index, 1);
    originalOrder.products.splice(index, 1);
    calculateTotal();
}

function calculateTotal() {
    total.value = selectedProducts.value.reduce((sum, product) => {
        try {
            return sum + (product.sale_price * product.pivot.quantity);
        } catch (error) {
            return sum + (product.sale_price * 1);
        }
    }, 0);
}

function submitOrder() {
    router.post(route('order.update'), {
        products: selectedProducts.value,
        client: client.id,
        order: order.id
    }, {
        onError: () => {
            showErrors.value = true;
        },
        onSuccess: () => {
            const successMessage = page.props.flash?.success;
            if (successMessage) {
                localStorage.setItem('flashSuccess', successMessage);
            }

            router.visit(route('order.edit', order.id));
        }
    });
}


function formatCurrency(value) {
    return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
}

function validateQuantity(product, index) {
    if (product.pivot.quantity > product.stock + (originalOrder.products[index]?.pivot.quantity ?? 0)) {
        alert('No puedes ingresar más cantidad que el stock disponible.');
        product.pivot.quantity = product.stock + (originalOrder.products[index]?.pivot.quantity ?? 0);
    } else if (product.pivot.quantity === 0) {
        alert('La cantidad mínima es 1.');
        product.pivot.quantity = 1;
    }
    calculateTotal();
}

function changeStatus(newStatus) {
    router.get(route('order.changestatus'), {
        status: newStatus,
        order: order.id
    }, {
        onError: () => {
            showErrors.value = true;
        },
        onSuccess: () => {
            const errorMessage = page.props.flash?.error;
            if (errorMessage) {
                localStorage.setItem('flashError', errorMessage);
            }

            const successMessage = page.props.flash?.success;
            if (successMessage) {
                localStorage.setItem('flashSuccess', successMessage);
            }

            router.visit(route('order.edit', order.id));
        }
    });
}

function deleteOrder() {
    router.delete(route('order.destroy', order.id), {
        onError: () => {
            showErrors.value = true;
        }
    });
}

function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function updateDueDate(index, newDate) {
    const original = new Date(this.order.installments[index].due_date);
    const [year, month, day] = newDate.split('-');

    original.setFullYear(+year, +month - 1, +day);
    this.order.installments[index].due_date = original.toISOString().slice(0, 19).replace('T', ' ');
}

function saveChanges(index) {
    const installment = this.order.installments[index];
    router.post(route('orders.installments.update'), installment, {
        onError: () => {
            showErrors.value = true;
        },
        onSuccess: () => {
            const errorMessage = page.props.flash?.error;
            if (errorMessage) {
                localStorage.setItem('flashError', errorMessage);
            }

            const successMessage = page.props.flash?.success;
            if (successMessage) {
                localStorage.setItem('flashSuccess', successMessage);
            }
            router.visit(route('order.edit', order.id))
        }
    })
    console.log('Guardando cambios en la cuota:', installment);
}

function markAsPaid(index) {
    this.order.installments[index].status = 'pagada';
    // Aquí podrías hacer una llamada a la API para guardar el cambio
}

onMounted(() => {
    const storedFlash = localStorage.getItem('flashSuccess');
    if (storedFlash) {
        flashSuccess.value = storedFlash;
        localStorage.removeItem('flashSuccess');
    }

    const storedFlashError = localStorage.getItem('flashError');
    if (storedFlashError) {
        flashError.value = storedFlashError;
        localStorage.removeItem('flashError');
    }

});
</script>
