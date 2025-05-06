<script setup lang="ts">
import DataTable from '@/Components/DataTable.vue';
import Heading from '@/Components/Heading.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { type BreadcrumbItem, PaginatedUsers } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, defineProps } from 'vue';

const { users: items } = defineProps<{
    users: PaginatedUsers;
}>();

const columns = [
    { key: 'id', label: 'ID', class: 'w-[100px]' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    {
        key: 'roles',
        label: 'Roles',
        type: 'badge',
        format: (roles: string[]) => roles.join(', '),
    },
    // {
    //     key: 'created_at',
    //     label: 'Created',
    //     type: 'date',
    //     class: 'text-right',
    //     format: (date: string) => new Date(date).toLocaleDateString(),
    // },
    {
        key: 'actions',
        label: 'Actions',
        type: 'actions',
        class: 'text-right',
    },
];

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: '/users' }];

const resource = 'users';
const createRoute = computed(() => `${resource}.create`);
const title = 'User management';
const description = 'Manage  users';
</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <Heading :title="title" :description="description" />
            </div>
            <DataTable title="" :resource="resource" :items="items" searchable :columns="columns" :create-route="createRoute">
                <!-- Custom badge rendering for roles -->
                <template #cell-roles="{ value }">
                    <div class="flex gap-1">
                        <span
                            v-for="role in value"
                            :key="role"
                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                        >
                            {{ role }}
                        </span>
                    </div>
                </template>
            </DataTable>
        </div>
    </AppLayout>
</template>
