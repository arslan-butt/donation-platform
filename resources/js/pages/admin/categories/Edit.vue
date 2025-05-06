<script setup lang="ts">
import CreateOrUpdate from '@/components/CreateOrUpdate.vue';
import { type Category, type SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
const category = page.props.category as Category;
</script>

<template>
    <CreateOrUpdate resource="categories" :item="category" form-label="category" form-key="category" />
</template>
