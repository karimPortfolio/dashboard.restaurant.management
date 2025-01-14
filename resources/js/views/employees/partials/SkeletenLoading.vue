<template>
    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 gap-y-14"
    >
        <q-card v-for="i in count" :key="i" >
            <q-card-section class="relative">
                <div class="flex justify-center w-full absolute -top-7 left-0">
                    <q-avatar
                        class="ring-4 ring-gray-100 dark:ring-slate-900 w-14 h-14"
                    >
                        <q-skeleton type="QAvatar" class="w-full h-full" :class="{
                            'bg-grey-4': !$q.dark.isActive,
                            'bg-dark': $q.dark.isActive,
                        }" />
                    </q-avatar>
                </div>
                <div class="flex justify-center font-medium mt-6 mb-3">
                    <q-skeleton type="text"  class="w-1/2" />
                </div>
                <div class="flex justify-between items-center mb-1">
                    <q-skeleton type="text" class="w-1/3" />
                    <div
                        class="flex justify-end text-sm text-gray-600 dark:text-gray-400 w-1/3"
                    >
                        <q-skeleton type="text" class="w-full" />
                    </div>
                </div>
                <div class="flex justify-between items-center mb-1">
                    <q-skeleton type="text" class="w-1/3" />
                    <div
                        class="flex justify-end text-sm text-gray-600 dark:text-gray-400 w-1/3"
                    >
                        <q-skeleton type="text" class="w-full"/>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-1">
                    <q-skeleton type="text" class="w-1/3" />
                    <div
                        class="flex justify-end text-sm text-gray-600 dark:text-gray-400 w-1/3"
                    >
                        <q-skeleton type="text"  class="w-full"/>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </div>
</template>
<script setup>

defineProps({
    count: {
        type: Number,
        default: 1
    }
})
</script>
