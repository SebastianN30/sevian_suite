<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-8 space-y-6 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg bg-white dark:bg-gray-800">
            <div class="flex justify-center p-6">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Ordenes
                </h2>
            </div>
            <div class="py-4">
                <div class="flex justify-center">
                    <div class="data-tabs">
                        <div class="data-tab">
                            <div class="number">{{ counts.complete }}</div>
                            <p class="title">Completadas</p>
                        </div>
                        <div class="data-tab">
                            <div class="number">{{ counts.pending }}</div>
                            <p class="title">Pendientes</p>
                        </div>
                        <div class="data-tab">
                            <div class="number">{{ counts.create }}</div>
                            <p class="title">Creada</p>
                        </div>
                        <div class="data-tab">
                            <div class="number">{{ counts.cancel }}</div>
                            <p class="title">Cancelada</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    counts: Object
});

</script>

<style scoped>
    .data-tabs {
    display: flex;
    width: 165%;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
    }

    .data-tab {
    flex: 1;
    min-width: 150px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    text-align: center;
    }

    .number {
    font-size: 2.5rem;
    color: #1A202C;
    font-weight: bold;
    }

    .title {
    margin-top: 10px;
    font-size: 1rem;
    color: #4A5568;
    }
</style>