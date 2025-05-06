<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Skeleton } from '@/Components/ui/skeleton';
import { useFormatters } from '@/Composables/useFormatters';
import type { Component } from 'vue';

type FormatterFn = (value: unknown) => string;

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number, BigInt],
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    icon: {
        type: Object as () => Component,
        required: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    valueFormatter: {
        type: Function as () => FormatterFn,
        default: (val: unknown) => String(val),
    },
});

const { currency, number, percent } = useFormatters();
</script>

<template>
    <Card>
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
                <template v-if="loading">
                    <Skeleton class="h-4 w-24" />
                </template>
                <template v-else>
                    {{ title }}
                </template>
            </CardTitle>
            <component :is="icon" class="text-muted-foreground h-4 w-4" v-if="!loading" />
            <Skeleton v-else class="h-4 w-4" />
        </CardHeader>
        <CardContent>
            <div class="text-2xl font-bold">
                <template v-if="loading">
                    <Skeleton class="h-8 w-full" />
                </template>
                <template v-else>
                    {{ valueFormatter(value) }}
                </template>
            </div>
            <p class="text-muted-foreground text-xs">
                <template v-if="loading">
                    <Skeleton class="mt-2 h-3 w-full" />
                </template>
                <template v-else>
                    {{ description }}
                </template>
            </p>
        </CardContent>
    </Card>
</template>
