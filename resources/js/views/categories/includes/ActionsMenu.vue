<template>
    <q-btn icon="more_horiz" padding="sm" size="sm" unelevated>
        <q-menu
            :offset="[0, 8]"
            class="q-pa-xs rounded-lg dark:bg-slate-700 menu-shadow w-[180px]"
        >
            <q-list style="min-width: 100px" dense class="q-pa-xs">
                <!-- ======= Show =======-->
                <q-item
                    clickable
                    v-close-popup
                    class="text-secondary dark:text-gray-200 rounded-md"
                >
                    <q-item-section avatar class="min-w-fit pe-2">
                        <q-icon name="visibility" size="xs" />
                    </q-item-section>

                    <q-item-section> Show </q-item-section>
                </q-item>

                <!--======= Edit =======-->
                <q-item
                    clickable
                    v-close-popup
                    class="text-secondary dark:text-gray-200 rounded-md"
                    @click="handleUpdate(category)"
                >
                    <q-item-section avatar class="min-w-fit pe-2">
                        <q-icon name="edit" size="xs" />
                    </q-item-section>

                    <q-item-section> Edit </q-item-section>
                </q-item>
                <!--======= Edit =======-->

                <!--======= DELETE =======-->
                <q-item clickable class="text-red-600 rounded-md" v-close-popup>
                    <q-item-section avatar class="min-w-fit pe-2">
                        <q-icon name="delete" size="xs" />
                    </q-item-section>

                    <q-item-section> Delete </q-item-section>
                </q-item>
                <!--======= DELETE =======-->
            </q-list>
        </q-menu>
    </q-btn>
</template>
<script setup>
const props = defineProps({
    category:{
        type: Object,
        required: true
    },
    handleUpdate: Function
});
</script>
