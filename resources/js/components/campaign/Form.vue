<script setup lang="ts">
import DatePicker from '@/components/DatePicker.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { usePermissions } from '@/composables/usePermissions';
import type { Campaign, CampaignStatus, Category } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
const { item } = defineProps<{
    item?: Campaign;
}>();

const page = usePage();
const categories = page.props.categories as Category[];
const statuses = page.props.statuses as CampaignStatus[];
const { isAdmin } = usePermissions();

const isEdit = !!item;
const form = useForm({
    title: item?.title || '',
    description: item?.description || '',
    initial_amount: item?.initial_amount || 0,
    target_amount: item?.target_amount || 0,
    start_date: item?.start_date || '',
    end_date: item?.end_date || '',
    status: item?.status || null,
    category_id: item?.category_id || null,
});

const submit = () => {
    const prefix = isAdmin.value ? 'admin.' : '';
    const base = 'campaigns';

    if (isEdit && item) {
        form.patch(route(`${prefix}${base}.update`, item.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route(`${prefix}${base}.store`), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <!-- Title Field -->
        <div class="grid gap-2">
            <Label for="title">Title *</Label>
            <Input id="title" v-model="form.title" required placeholder="Campaign name" />
            <InputError :message="form.errors.title" />
        </div>

        <!-- Description Field -->
        <div class="grid gap-2">
            <Label for="description">Description *</Label>
            <Textarea id="description" v-model="form.description" required placeholder="Description" />
            <InputError :message="form.errors.description" />
        </div>

        <!-- Target Amount Field -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="grid gap-2">
                <Label for="initial_amount">Initial Amount </Label>
                <Input id="initial_amount" type="number" v-model="form.initial_amount" min="0" step="0.01" />
                <InputError :message="form.errors.initial_amount" />
            </div>

            <div class="grid gap-2">
                <Label for="target_amount">Target Amount *</Label>
                <Input id="target_amount" type="number" v-model="form.target_amount" required min="0" step="0.01" />
                <InputError :message="form.errors.target_amount" />
            </div>
        </div>
        <!-- Date Fields -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="grid gap-2">
                <Label for="start_date">Start Date *</Label>
                <DatePicker id="start_date" v-model="form.start_date" placeholder="Start date" required />
                <InputError :message="form.errors.start_date" />
            </div>
            <div class="grid gap-2">
                <Label for="end_date">End Date *</Label>
                <DatePicker id="end_date" v-model="form.end_date" placeholder="End date" required />
                <InputError :message="form.errors.end_date" />
            </div>
        </div>

        <!-- Status Field -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="grid gap-2">
                <Label for="status">Status *</Label>
                <Select v-model="form.status" required>
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="status in statuses" :key="status.value" :value="status.value" class="w-full">
                            {{ status.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.status" />
            </div>

            <!-- Category Field -->
            <div class="grid gap-2">
                <Label for="category">Category *</Label>
                <Select v-model="form.category_id" required>
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select category" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.category_id" />
            </div>
        </div>

        <!-- Submit Button -->
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
