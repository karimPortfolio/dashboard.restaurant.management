<template>
    <q-btn
        outline
        padding="0"
        class="aspect-square before:border-gray-300 bg-gray-200 dark:bg-gray-600 dark:text-white text-dark"
        :class="width"
    >
        <slot name="content"></slot>
    </q-btn>
</template>
<script setup>

defineProps({
    width: {
        type: String,
        default: "w-6"
    },
})

</script>
