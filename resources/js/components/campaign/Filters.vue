<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { CampaignStatus, Category } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';

const props = defineProps<{
    filters: {
        search?: string;
        category?: string;
        status?: string;
        featured?: boolean;
        date_range?: [Date, Date];
    };
    categories?: Category[];
    statusOptions?: CampaignStatus[];
}>();

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || 'all'); // Changed default to 'all' instead of empty string
const status = ref(props.filters.status || 'all'); // Changed default to 'all' instead of empty string
const featured = ref(props.filters.featured || false);
const dateRange = ref<[Date, Date] | undefined>(props.filters.date_range);

const updateFilters = debounce(() => {
    router.get(
        route('campaigns.all'),
        {
            search: search.value,
            category: category.value === 'all' ? '' : category.value, // Convert 'all' to empty string for the backend
            status: status.value === 'all' ? '' : status.value, // Convert 'all' to empty string for the backend
            featured: featured.value,
            date_range: dateRange.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
}, 300);

watch([search, category, status, featured, dateRange], () => {
    updateFilters();
});

const clearFilters = () => {
    search.value = '';
    category.value = 'all'; // Reset to 'all' instead of empty string
    status.value = 'all'; // Reset to 'all' instead of empty string
    featured.value = false;
    dateRange.value = undefined;
};
</script>

<template>
    <div class="mb-6 space-y-4">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search Input -->
            <Input v-model="search" placeholder="Search campaigns..." class="max-w-xs" />

            <!-- Category Filter -->
            <Select v-model="category">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Select category" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Categories</SelectItem>
                    <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <!-- Status Filter -->
            <Select v-model="status">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Select status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Statuses</SelectItem>
                    <SelectItem v-for="status in statusOptions" :key="status.value" :value="status.value">
                        {{ status.label }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <!-- Featured Toggle -->
            <!-- <div class="flex items-center space-x-2">
                <Checkbox id="featured" v-model:checked="featured" />
                <Label for="featured">Featured Only</Label>
            </div> -->

            <!-- Date Range Picker -->
            <!-- <Popover>
                <PopoverTrigger as-child>
                    <Button variant="outline" class="w-[240px] justify-start">
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ dateRange ? `${dateRange[0].toLocaleDateString()} - ${dateRange[1].toLocaleDateString()}` : 'Select date range' }}
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-auto p-0">
                    <Calendar v-model.range="dateRange" mode="range" :number-of-months="2" />
                </PopoverContent>
            </Popover> -->

            <!-- Reset Filters -->
            <Link :href="route('campaigns.all')" class="text-muted-foreground text-sm hover:underline" @click="clearFilters"> Clear Filters </Link>
        </div>
    </div>
</template>
