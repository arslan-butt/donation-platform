<script setup lang="ts">
// import CustomStatusBadge from '@/components/CustomStatusBadge.vue';
import DataTable from '@/components/DataTable.vue';
import Heading from '@/components/Heading.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, PaginatedCampaigns } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, markRaw, ref } from 'vue';

const { campaigns: items } = defineProps<{
    campaigns: PaginatedCampaigns;
}>();

// const { campaigns: items } = props;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Compaigns',
        href: '/admin/campaigns',
    },
];

// Reactive columns
const columns = ref([
    // { key: 'id', label: 'ID', class: 'w-[100px]' },
    { key: 'title', label: 'Title', sortable: true },
    { key: 'description', label: 'Description' },
    { key: 'target_amount', label: 'Target amount' },
    { key: 'initial_amount', label: 'Initial amount' },
    { key: 'progress', label: 'Progress (%)' },
    {
        key: 'status',
        label: 'Status',
        type: 'custom',
        component: markRaw(StatusBadge),
    },
    { key: 'start_date', label: 'Start date' },
    { key: 'end_date', label: 'End date' },
    // { key: 'status', label: 'Status', type: 'custom', component: CustomStatusBadge },
    { key: 'category', label: 'Category' },

    { key: 'author', label: 'Created by' },
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
const resource = 'campaigns';
const hasEditPermission = computed(() => true);
const createRoute = computed(() => `${resource}.create`);
const title = 'Comapaigns';
const description = 'Manage your comapaigns';
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="title" />
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <Heading :title="title" :description="description" />
            </div>
            <DataTable title="" :resource="resource" :items="items" searchable :columns="columns" :create-route="createRoute" />
        </div>
    </AppLayout>
</template>
