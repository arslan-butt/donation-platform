<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42">
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M20 6.5c-2.5-3.5-8-3.5-10.5 0C7 10 7 15 9.5 18.5L20 29l10.5-10.5c2.5-3.5 2.5-8.5 0-12-2.5-3.5-8-3.5-10.5 0ZM3 29h7l-3.5 7H3v-7Zm34 0h-7l3.5 7H37v-7ZM0 38h40v4H0v-4Zm7-15h26v6H7v-6Zm10-7.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5Zm16 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5Z"
        />
    </svg>
</template>
