<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination';
import { router } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

const {
    pagination = {
        current_page: 1,
        per_page: 10,
        total: 0,
        last_page: 1, // Using Laravel's built-in last_page
        links: [] as PaginationLink[],
        first_page_url: '',
        last_page_url: '',
        next_page_url: null,
        prev_page_url: null,
    },
    only = [],
} = defineProps<{
    pagination: {
        current_page: number;
        per_page: number;
        total: number;
        last_page: number; // Now properly typed
        links: PaginationLink[];
        first_page_url: string;
        last_page_url: string;
        next_page_url: string | null;
        prev_page_url: string | null;
    };
    only?: string[];
}>();

function navigateToPage(url: string | null) {
    if (!url) return;
    router.visit(url, {
        only,
        preserveState: true,
        preserveScroll: true,
    });
}

function getKey(link: PaginationLink, index: number) {
    return link.label !== '...' ? `page-${link.label}-${index}` : `ellipsis-${index}`;
}
</script>

<template>
    <div class="flex items-center justify-between px-2">
        <Pagination :items-per-page="pagination.per_page" :total-items="pagination.total" :current-page="pagination.current_page">
            <PaginationList class="flex items-center gap-1">
                <!-- First Page -->
                <PaginationFirst @click="navigateToPage(pagination.first_page_url)" :disabled="pagination.current_page === 1" />

                <!-- Previous Page -->
                <PaginationPrev @click="navigateToPage(pagination.prev_page_url)" :disabled="!pagination.prev_page_url" />

                <!-- Page Numbers -->
                <template v-for="(link, index) in pagination.links.slice(1, -1)" :key="getKey(link, index)">
                    <PaginationListItem v-if="link.label !== '...'" as-child>
                        <Button class="h-9 w-9 p-0" :variant="link.active ? 'default' : 'outline'" @click="navigateToPage(link.url)">
                            <span v-html="link.label" />
                        </Button>
                    </PaginationListItem>
                    <PaginationEllipsis v-else />
                </template>

                <!-- Next Page -->
                <PaginationNext @click="navigateToPage(pagination.next_page_url)" :disabled="!pagination.next_page_url" />

                <!-- Last Page -->
                <PaginationLast @click="navigateToPage(pagination.last_page_url)" :disabled="pagination.current_page === pagination.last_page" />
            </PaginationList>
        </Pagination>
    </div>
</template>
