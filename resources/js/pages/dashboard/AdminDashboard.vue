<script setup lang="ts">
import StatCard from '@/components/dashboard/StatCard.vue';
import { useFormatters } from '@/composables/useFormatters';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Deferred, Head } from '@inertiajs/vue3';
import { Activity, CheckCircle, DollarSign, HeartHandshake, LayoutGrid, Users } from 'lucide-vue-next';

const { currency, number } = useFormatters();

const props = defineProps<{
    user_count?: number;
    active_campaigns: number;
    completed_campaigns: number;
    total_raised: number;
    total_donations: number;
    total_campaigns: number;
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <!-- Immediate Stat -->
                <StatCard title="Total Users" :value="user_count" description="Registered users" :icon="Users" :value-formatter="number" />

                <Deferred data="total_campaigns">
                    <template #fallback>
                        <StatCardSkeleton />
                    </template>
                    <StatCard
                        title="Total Campaigns"
                        :value="total_campaigns"
                        description="All campaigns"
                        :icon="LayoutGrid"
                        :value-formatter="number"
                    />
                </Deferred>

                <!-- Active Campaigns -->
                <Deferred data="active_campaigns">
                    <template #fallback>
                        <StatCardSkeleton />
                    </template>
                    <StatCard
                        title="Active Campaigns"
                        :value="active_campaigns"
                        description="Currently running"
                        :icon="Activity"
                        :value-formatter="number"
                    />
                </Deferred>

                <!-- Completed Campaigns -->
                <Deferred data="completed_campaigns">
                    <template #fallback>
                        <StatCardSkeleton />
                    </template>
                    <StatCard
                        title="Completed Campaigns"
                        :value="completed_campaigns"
                        description="Successful campaigns"
                        :icon="CheckCircle"
                        :value-formatter="number"
                    />
                </Deferred>

                <!-- Total Raised -->
                <Deferred data="total_raised">
                    <template #fallback>
                        <StatCardSkeleton />
                    </template>
                    <StatCard
                        title="Total Raised"
                        :value="total_raised"
                        description="All-time donations"
                        :icon="DollarSign"
                        :value-formatter="currency"
                    />
                </Deferred>

                <!-- Total Donations -->
                <Deferred data="total_donations">
                    <template #fallback>
                        <StatCardSkeleton />
                    </template>
                    <StatCard
                        title="Total Donations"
                        :value="total_donations"
                        description="Completed donations"
                        :icon="HeartHandshake"
                        :value-formatter="number"
                    />
                </Deferred>
            </div>
        </div>
    </AppLayout>
</template>
