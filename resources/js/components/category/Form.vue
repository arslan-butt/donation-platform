<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { type Category } from '@/types';
import { useForm } from '@inertiajs/vue3';

const { item } = defineProps<{
    item?: Category;
}>();

const isEdit = !!item;
const form = useForm({
    name: item?.name || '',
    description: item?.description || '',
});

const submit = () => {
    if (isEdit && item) {
        form.patch(route('admin.categories.update', item.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.categories.store'), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Category name" />
            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="grid gap-2">
            <Label for="description">Description</Label>
            <Textarea id="description" class="mt-1 block w-full" v-model="form.description" autocomplete="description" placeholder="Description" />
            <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <div class="flex items-center gap-4">
            <Button :disabled="form.processing">
                {{ isEdit ? 'Update' : 'Create' }}
            </Button>
            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">
                    {{ isEdit ? 'Updated.' : 'Saved.' }}
                </p>
            </Transition>
        </div>
    </form>
</template>
