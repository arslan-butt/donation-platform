<script setup lang="ts">
import CampaignCard from '@/components/campaign/Card.vue';
import CampaignFilters from '@/components/campaign/Filters.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { CampaignStatus, Category, Paginatedcampaigns, Pagination } from '@/types';
import { Deferred, Head, WhenVisible } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    campaigns: Paginatedcampaigns;
    categories?: Category[];
    statuses?: CampaignStatus[];
    filters: {
        search?: string;
        category?: string;
        status?: string;
        featured?: boolean;
        date_range?: [Date, Date];
        per_page?: number;
    };
    pagination: Pagination;
}>();

const reachingEnd = computed(() => {
    return props.pagination.current_page >= props.pagination.last_page;
});
</script>

<template>
    <AppLayout>
        <Head :title="'All Campaigns'" />

        <div class="px-4 py-6">
            <div class="mb-6">
                <Heading title="All Campaigns" description="Discover and support meaningful initiatives" />
                <Deferred :data="['categories', 'statuses']">
                    <template #fallback>
                        <div>Loading...</div>
                    </template>

                    <CampaignFilters :categories="categories" :status-options="statuses" :filters="filters" />
                </Deferred>
            </div>

            <div v-if="campaigns && campaigns.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <CampaignCard v-for="campaign in campaigns" :key="campaign.id" :campaign="campaign" class="hover:shadow-lg" />
            </div>
            <div v-else class="py-12 text-center">
                <p class="text-gray-500">No campaigns found matching your filters.</p>
            </div>

            <!-- Load More if have next page -->

            <WhenVisible
                :always="!reachingEnd"
                :params="{
                    only: ['campaigns', 'pagination'],
                    data: {
                        ...filters,
                        page: pagination.current_page + 1,
                    },
                }"
            >
                <template #fallback>
                    <div class="p-4 text-center text-gray-500">Loading more campaigns...</div>
                </template>
            </WhenVisible>
        </div>
    </AppLayout>
</template>
