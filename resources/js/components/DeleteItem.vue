<!-- components/common/DeleteDialog.vue -->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

// Components
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

const emit = defineEmits(['deleted']);

const { resource, itemId, title, description } = defineProps<{
    resource: string;
    itemId: string | number;
    title?: string;
    description?: string;
}>();

const open = ref(false);
const form = useForm({});

const deleteItem = () => {
    form.delete(route(`${resource}.destroy`, itemId), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            emit('deleted');
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button variant="destructive" size="sm">
                <Trash2Icon class="h-4 w-4" />
            </Button>
        </DialogTrigger>

        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title || `Delete ${resource}` }}</DialogTitle>
                <DialogDescription>
                    {{ description || `Are you sure you want to delete this ${resource}? This action cannot be undone.` }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="mt-6 gap-2">
                <Button variant="secondary" @click="open = false"> Cancel </Button>
                <Button variant="destructive" :disabled="form.processing" @click="deleteItem"> Delete </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
