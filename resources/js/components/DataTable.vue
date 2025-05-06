<script setup lang="ts">
import AppPagination from '@/Components/AppPagination.vue';
import DeleteItem from '@/components/DeleteItem.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { usePermissions } from '@/composables/usePermissions';
import type { PaginatedResponse } from '@/types'; // Import your generic type
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { PencilIcon, PlusIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface TableColumn {
    key: string;
    label: string;
    type?: 'text' | 'date' | 'badge' | 'actions' | 'custom';
    format?: (value: any) => string;
    class?: string;
    sortable?: boolean;
    component?: any;
}

const { title, resource, items, columns, searchable, createRoute, filters } = defineProps<{
    title: string;
    resource: string;
    items: PaginatedResponse<any>; // Using your generic PaginatedResponse
    columns: TableColumn[];
    searchable?: boolean;
    createRoute?: string;
    filters?: {
        search?: string;
    };
}>();

const { isAdmin } = usePermissions();
const searchQuery = ref<string>(filters?.search || '');
const resourcePrefix = computed(() => (isAdmin.value ? 'admin.' : ''));

const getRouteName = (action: string) => {
    const routeName = `${resourcePrefix.value}${resource}.${action}`;
    return route().has(routeName) ? routeName : null;
};

const doSearch = debounce((q: string) => {
    const routeName = getRouteName('index');
    if (routeName) {
        router.get(
            route(routeName),
            { search: q },
            {
                preserveState: true,
                replace: true,
            },
        );
    }
}, 300);

if (searchable) {
    watch(searchQuery, doSearch);
}

const formatValue = (item: any, column: TableColumn) => {
    if (column.format) return column.format(item[column.key]);
    switch (column.type) {
        case 'date':
            return new Date(item[column.key]).toLocaleDateString();
        case 'badge':
            const values = Array.isArray(item[column.key]) ? item[column.key] : [item[column.key]];
            return values.map((v) => v).join(', ');
        default:
            return item[column.key];
    }
};

const handleDeleteSuccess = () => {
    router.reload({ only: ['items'] });
};
const isEmpty = computed(() => items?.data?.length === 0);
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ title }}</h2>
            <div class="flex gap-4">
                <div v-if="searchable" class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search..."
                        class="border-input bg-background ring-offset-background h-10 rounded-md border px-3 py-2 text-sm"
                    />
                </div>
                <Link v-if="createRoute" :href="route(getRouteName('create'))">
                    <Button>
                        <PlusIcon />
                        Add New
                    </Button>
                </Link>
            </div>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader class="bg-gray-50">
                    <TableRow>
                        <TableHead v-for="column in columns" :key="column.key" :class="column.class">
                            {{ column.label }}
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="isEmpty">
                        <TableCell :colspan="columns.length" class="py-8 text-center">
                            <div class="flex flex-col items-center justify-center gap-2 text-gray-500">
                                <span class="text-lg">No items found</span>
                                <Link v-if="createRoute" :href="route(getRouteName('create'))" class="text-primary hover:underline">
                                    Create your first item
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>

                    <TableRow v-else v-for="item in items?.data" :key="item.id">
                        <TableCell v-for="column in columns" :key="`${item.id}-${column.key}`" class="max-w-[200px] break-words whitespace-normal">
                            <div v-if="column.type === 'actions'" class="flex justify-end gap-2">
                                <Link :href="route(getRouteName('edit'), item.id)">
                                    <Button variant="outline" size="sm">
                                        <PencilIcon class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <DeleteItem
                                    :resource="isAdmin ? 'admin.' + resource : resource"
                                    :itemId="item.id"
                                    title="Delete Item"
                                    description="Are you sure you want to delete this item?"
                                    @deleted="handleDeleteSuccess"
                                />
                            </div>
                            <div v-else-if="column.type === 'badge'" class="flex gap-1">
                                <span
                                    v-for="(val, idx) in Array.isArray(item[column.key]) ? item[column.key] : [item[column.key]]"
                                    :key="idx"
                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800"
                                >
                                    {{ val }}
                                </span>
                            </div>

                            <component v-else-if="column.type === 'custom'" :is="column.component" :status="item[column.key]" />
                            <div v-else>
                                {{ formatValue(item, column) }}
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <AppPagination v-if="!isEmpty" :pagination="items" class="mt-auto" />
    </div>
</template>
