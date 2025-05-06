<script setup lang="ts">
// import CustomStatusBadge from '@/components/CustomStatusBadge.vue';
import DataTable from '@/components/DataTable.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, PaginatedCategories } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const { categories: items } = defineProps<{
    categories: PaginatedCategories;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Categories',
        href: '/admin/categories',
    },
];

// Reactive columns
const columns = ref([
    { key: 'id', label: 'ID', class: 'w-[100px]' },
    { key: 'name', label: 'Name', sortable: true },
    { key: 'description', label: 'Description' },

    // { key: 'author.name', label: 'Author' },
    // { key: 'created_at', label: 'Created', type: 'date', class: 'text-right' },
    {
        key: 'actions',
        label: 'Actions',
        type: 'actions',
        class: 'text-right',
        hidden: computed(() => !hasEditPermission.value), // Reactively hide actions
    },
]);

// Constants
const resource = 'categories';
const hasEditPermission = computed(() => true);
const createRoute = computed(() => `${resource}.create`);
const title = 'Categories';
const description = 'Manage your categories';
</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <Heading :title="title" :description="description" />
            </div>
            <DataTable title="" :resource="resource" :items="items" searchable :columns="columns" :create-route="createRoute" />
        </div>
    </AppLayout>
</template>
