<script setup lang="ts">
import Heading from '@/Components/Heading.vue';
import AppFormLayout from '@/layouts/AppFormLayout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, defineAsyncComponent } from 'vue';

interface Props {
    resource: string; // 'categories', 'users', 'campaigns'
    formLabel: string;
    formKey: string;
    item?: Record<string, any>;
}

const { resource, formLabel, formKey, item } = defineProps<Props>();

const isEdit = computed(() => !!item);

const pageTitle = computed(() => `${isEdit.value ? 'Edit' : 'Create'} ${formLabel}`);
const pageDescription = computed(() => (isEdit.value ? `Update existing ${formLabel}` : `Create a new ${formLabel} `));

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: pageTitle,
        href: isEdit.value ? `/admin/${resource}/${formKey?.id}/edit` : `/admin/${resource}/create`,
    },
]);

// Dynamic form component
const FormComponent = defineAsyncComponent(() => import(`@/components/${formKey}/Form.vue`));
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="pageTitle" />
        <AppFormLayout>
            <div class="flex flex-col space-y-6">
                <Heading :title="pageTitle" :description="pageDescription" />
                <component :is="FormComponent" :item="item" />
            </div>
        </AppFormLayout>
    </AppLayout>
</template>
