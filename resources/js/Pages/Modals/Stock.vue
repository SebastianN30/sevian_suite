<template>
    <!-- Modal Container -->
    <div v-if="isOpen" class="fixed inset-0 z-50 bg-black bg-opacity-25">
        <div class="flex justify-center items-center h-full w-full p-4">
            <div class="w-full max-w-md">
                <!-- Formulario para ajustar stock -->
                <form @submit.prevent="submitForm" class="bg-white rounded-xl shadow-xl dark:bg-gray-800 overflow-hidden transition-all duration-300 transform">
                    <!-- Encabezado -->
                    <div class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Ajustar stock
                        </h3>
                        <button type="button" @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="sr-only">Cerrar modal</span>
                        </button>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="p-5">
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-medium text-gray-800 dark:text-gray-200 mb-2">Producto seleccionado:</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nombre:</p>
                                    <p class="font-medium text-white">{{ form.product.name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Stock actual:</p>
                                    <p class="font-medium text-white">{{ form.product.stock }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="stock_adjustment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ajuste de stock
                            </label>
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    @click="adjustStock(-1)"
                                    class="p-2 rounded-md bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                    :disabled="form.adjustment <= 0"
                                >
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                
                                <input 
                                    type="number" 
                                    id="stock_adjustment" 
                                    v-model.number="form.adjustment"
                                    class="flex-1 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm text-center"
                                    min="0"
                                    required
                                >
                                
                                <button 
                                    type="button" 
                                    @click="adjustStock(1)"
                                    class="p-2 rounded-md bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                >
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Ingrese la cantidad a agregar (positivo) o restar (negativo)
                            </p>
                        </div>

                        <!-- Nuevo stock calculado -->
                        <div class="p-3 bg-blue-50 dark:bg-gray-700 rounded-lg mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Nuevo stock:</p>
                            <p class="text-lg font-bold" :class="{
                                'text-green-600': form.adjustment > 0,
                                'text-red-600': form.adjustment < 0,
                                'text-gray-600': form.adjustment === 0
                            }">
                                {{ calculatedStock }}
                            </p>
                        </div>
                        <!-- Notas -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notas (opcional)
                            </label>
                            <textarea 
                                id="note" 
                                v-model="form.note"
                                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                rows="2"
                                placeholder="Motivo del ajuste..."
                            ></textarea>
                        </div>
                    </div>
                    <!-- Pie del modal -->
                    <div class="flex justify-end gap-3 p-5 bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-700">
                        <button 
                            type="button" 
                            @click="closeModal" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50"
                            :disabled="form.adjustment === 0 || isSubmitting"
                        >
                            <span v-if="!isSubmitting">Guardar cambios</span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps(['isOpen', 'product']);

const emit = defineEmits(['close', 'updated']);

// Formulario reactivo
const form = ref({
    product: props.product,
    adjustment: 0,
    note: props.product?.note || ''
});

const isSubmitting = ref(false);

// Calcula el nuevo stock
const calculatedStock = computed(() => {
    return form.value.product.stock + form.value.adjustment;
});

// Ajusta el valor en +1 o -1
const adjustStock = (value) => {
    const newValue = form.value.adjustment + value;
    form.value.adjustment = Math.max(newValue, -form.value.product.stock);
};

// Envía el formulario
const submitForm = async () => {
    isSubmitting.value = true;
    
    try {
        await router.get(
                route('product.updateStock', form.value.product.id), {
                adjustment: form.value.adjustment,
                note: form.value.note
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    emit('updated');
                    closeModal();
                },
                onError: (errors) => {
                    console.error('Error al actualizar el stock:', errors);
                },
                onFinish: () => {
                    isSubmitting.value = false;
                }
            }
        );
    } catch (error) {
        console.error('Error:', error);
        isSubmitting.value = false;
    }
};

// Resetea el formulario al cerrar
const closeModal = () => {
    form.value = {
        product: props.product || { note: '' },
        adjustment: 0,
        note: props.product?.note || ''
    };
    emit('close');
};
// Actualiza el producto cuando cambia la prop
watch(() => props.product, (newProduct) => {
    if (!newProduct) return;
    form.value.product = newProduct;
    form.value.note = newProduct.note || '';
}, { deep: true });
</script>