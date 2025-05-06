<script setup lang="ts">
import CreateOrUpdate from '@/Components/CreateOrUpdate.vue';
import { type Campaign, type SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
const campaign = page.props.campaign as Campaign;
</script>

<template>
    <CreateOrUpdate resource="campaigns" :item="campaign" form-label="campaign" form-key="campaign" />
</template>
