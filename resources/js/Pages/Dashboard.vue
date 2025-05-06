<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-900">
                    <div class="flex justify-center">
                        <div class="data-tabs">
                            <div class="data-tab">
                                <div class="number">{{ counts.complete }}</div>
                                <p class="title">Completadas</p>
                            </div>
                            <div class="data-tab">
                                <div class="number">{{ counts.pending }}</div>
                                <p class="title">Pendiente</p>
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