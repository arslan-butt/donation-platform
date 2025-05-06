<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton'; /* PartiallyEnd: #3632/scriptSetup.vue */
</script>

<template>
    <Card>
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
                <Skeleton class="h-4 w-[100px]" />
            </CardTitle>
            <Skeleton class="h-4 w-4 rounded-full" />
        </CardHeader>
        <CardContent>
            <div class="space-y-2">
                <Skeleton class="h-7 w-full max-w-[200px]" />
                <Skeleton class="h-4 w-full max-w-[150px]" />
            </div>
        </CardContent>
    </Card>
</template>
