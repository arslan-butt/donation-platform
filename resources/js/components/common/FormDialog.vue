<!-- components/common/FormDialog.vue -->
<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

const openModel = defineModel<boolean>('open', { default: false });

defineProps<{
    title: string;
    description?: string;
    triggerText?: string;
}>();
</script>

<template>
    <Dialog v-model:open="openModel">
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>

        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription v-if="description">
                    {{ description }}
                </DialogDescription>
            </DialogHeader>

            <slot />
        </DialogContent>
    </Dialog>
</template>
