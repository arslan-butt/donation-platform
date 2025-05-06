<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AdminDashboard from '@/pages/dashboard/AdminDashboard.vue';
import UserDashboard from '@/pages/dashboard/UserDashboard.vue';

const { isAdmin } = usePermissions();
</script>

<template>
    <div>
        <AdminDashboard v-if="isAdmin" />
        <UserDashboard v-else />
    </div>
</template>
