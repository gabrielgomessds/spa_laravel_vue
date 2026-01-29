<script setup>
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const deleteRow = (id) => {
    if (window.confirm("Tem certeza que deseja deletar esse produto?")) {
        router.delete(route('products.destroy', id), {
            preserveScroll: true
        })
    }
}

defineProps({
    products: {
        type: Object,
        required: true
    }
});
</script>

<template>

<Head title="Produtos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Todos os produtos</h2>
                <Link :href="route('products.create')"
                    class="px-3 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-md hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                Adicionar Produto</Link>
            </div>

        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3" with="5">
                                        Nº
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Categoria
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Preço
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Peso
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in products.data" :key="product.id"
                                    class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ products.meta.from + index }}
                                    </th>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ product.name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ product.category.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ product.price_formatted }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ product.weight }}
                                    </td>
                                    <td class="px-6 py-4 space-x-2">
                                        <Link :href="route('products.show', product.id)" class="font-medium text-gray-600 hover:underline">Ver</Link>
                                        <Link :href="route('products.edit', product.id)" class="font-medium text-blue-600 hover:underline">Editar</Link>
                                        <a href="#" class="font-medium text-red-600 hover:underline" @click.prevent="deleteRow(product.id)">Deletar</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :meta="products.meta"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
