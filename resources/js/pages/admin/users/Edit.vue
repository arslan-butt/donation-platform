<script setup lang="ts">
import CreateOrUpdate from '@/components/CreateOrUpdate.vue';
import { type SharedData, type UserFormData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
const item = page.props.user as UserFormData;
</script>

<template>
    <CreateOrUpdate resource="users" :item="item" form-label="user" form-key="user" />
</template>
