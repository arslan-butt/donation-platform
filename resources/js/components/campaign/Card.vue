//components/campaign/Card.vue
<script setup lang="ts">
import DonationForm from '@/components/donation/Form.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Campaign } from '@/types';
import { Clock, Heart, Share2, Target } from 'lucide-vue-next';
import { computed, defineProps, ref } from 'vue';

const { campaign, class: className } = defineProps<{
    campaign: Campaign;
    class?: string;
}>();

// State management
const showDonationForm = ref(false);
const isFavorite = ref(false);

// Calculate dates and remaining time
const dates = computed(() => ({
    start: new Date(campaign.start_date).toLocaleDateString(),
    end: new Date(campaign.end_date).toLocaleDateString(),
    remaining: formatDateDifference(campaign.end_date),
}));

// Status configuration with dynamic styling
const statusConfig = computed(() => {
    switch (campaign.status.toLowerCase()) {
        case 'active':
            return { label: 'Active', class: 'bg-green-100 text-green-800' };
        case 'completed':
            return { label: 'Completed', class: 'bg-blue-100 text-blue-800' };
        case 'expired':
            return { label: 'Expired', class: 'bg-red-100 text-red-800' };
        default:
            return { label: campaign.status, class: 'bg-gray-100 text-gray-800' };
    }
});

// Helper function for date difference
function formatDateDifference(endDate: string) {
    const end = new Date(endDate);
    const now = new Date();
    const diffTime = end.getTime() - now.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays <= 0) {
        return 'Campaign ended';
    } else if (diffDays === 1) {
        return '1 day remaining';
    } else {
        return `${diffDays} days remaining`;
    }
}

// Toggle favorite state
function toggleFavorite() {
    isFavorite.value = !isFavorite.value;
    // TODO: Add API call to save favorite status if needed
}

// Open donation form
function openDonation() {
    showDonationForm.value = true;
}

// Handle sharing
function shareCampaign() {
    console.log(`Sharing campaign: ${campaign.title}`);
}
</script>

<template>
    <Card :class="['w-full transition-all hover:shadow-lg', className]">
        <!-- Featured image if available -->
        <div v-if="campaign.featured" class="relative h-40 w-full overflow-hidden rounded-t-lg">
            <img :src="campaign.featured" :alt="campaign.title" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-30"></div>
        </div>

        <CardContent class="p-4">
            <div class="mb-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span v-if="campaign.category" class="bg-secondary text-secondary-foreground rounded-full px-2 py-1 text-xs">
                        {{ campaign.category }}
                    </span>
                    <span class="rounded-full px-2 py-1 text-xs" :class="statusConfig.class">
                        {{ statusConfig.label }}
                    </span>
                </div>
                <Button variant="ghost" size="sm" class="h-8 w-8 rounded-full p-2" @click="toggleFavorite">
                    <Heart class="h-4 w-4" :class="{ 'fill-red-500 text-red-500': isFavorite }" />
                </Button>
            </div>

            <CardTitle class="mb-1 line-clamp-1 text-lg">
                {{ campaign.title }}
            </CardTitle>

            <CardDescription class="mb-3 line-clamp-2">
                {{ campaign.description }}
            </CardDescription>

            <div class="space-y-3">
                <div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-muted-foreground"> ${{ campaign.total_amount }} raised </span>
                        <span class="font-medium">{{ campaign.progress }}%</span>
                    </div>
                    <Progress :model-value="campaign.progress > 100 ? 100 : parseInt(campaign.progress)" class="h-2" />
                </div>

                <div class="flex justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <Target class="text-muted-foreground h-4 w-4" />
                        <span>${{ campaign.target_amount }} goal</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Clock class="text-muted-foreground h-4 w-4" />
                        <span>{{ dates.remaining }}</span>
                    </div>
                </div>

                <div v-if="campaign.author" class="text-muted-foreground flex items-center gap-2 text-sm">
                    <span>By {{ campaign.author }}</span>
                    <span v-if="campaign.created_at" class="text-xs">· {{ campaign.created_at }}</span>
                </div>
            </div>
        </CardContent>

        <CardFooter class="flex justify-between p-4 pt-0">
            <Button variant="outline" size="sm" class="gap-2" @click="shareCampaign">
                <Share2 class="h-4 w-4" />
                Share
            </Button>

            <Button size="sm" class="gap-2" @click="openDonation"> Donate Now </Button>
        </CardFooter>
    </Card>

    <!-- Donation Modal -->
    <DonationForm v-if="showDonationForm" :campaign="campaign" :show="showDonationForm" @close="showDonationForm = false" />
</template>
