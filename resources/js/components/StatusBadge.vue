<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { computed } from 'vue';

type Status = 'draft' | 'pending_approval' | 'active' | 'completed' | 'cancelled';
type BadgeVariant = 'default' | 'destructive' | 'outline' | 'secondary';

const { status } = defineProps<{
    status: Status;
}>();

interface StatusConfig {
    variant: BadgeVariant;
    label: string;
    class: string;
}

const statusConfig = computed<StatusConfig>(() => {
    const baseClasses = ' text-xs font-medium ring-1 ring-inset';

    switch (status) {
        case 'draft':
            return {
                variant: 'outline',
                label: 'Draft',
                class: `${baseClasses} bg-gray-50 text-gray-600 ring-gray-500/10`,
            };
        case 'pending_approval':
            return {
                variant: 'secondary',
                label: 'Pending Approval',
                class: `${baseClasses} bg-yellow-50 text-yellow-800 ring-yellow-600/20`,
            };
        case 'active':
            return {
                variant: 'default',
                label: 'Active',
                class: `${baseClasses} bg-green-50 text-green-700 ring-green-600/20`,
            };
        case 'completed':
            return {
                variant: 'default',
                label: 'Completed',
                class: `${baseClasses} bg-blue-50 text-blue-700 ring-blue-700/10`,
            };
        case 'cancelled':
            return {
                variant: 'destructive',
                label: 'Cancelled',
                class: `${baseClasses} bg-red-50 text-red-700 ring-red-600/10`,
            };
        default:
            return {
                variant: 'outline',
                label: 'Unknown',
                class: `${baseClasses} bg-gray-50 text-gray-600 ring-gray-500/10`,
            };
    }
});
</script>

<template>
    <Badge :variant="statusConfig.variant" :class="statusConfig.class">
        {{ statusConfig.label }}
    </Badge>
</template>
