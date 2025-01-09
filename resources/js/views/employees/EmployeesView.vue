<template>
    <section class="px-4">
        <!-- ==================== // PAGE HEADER //==================== -->
        <PageHeader
            title="Employees"
            caption="Manage your employees"
            actionIcon="sym_r_add"
            actionLabel="Add Employee"
            :action="handleCreate"
        />
        <!-- ==================== // PAGE HEADER //==================== -->

        <div class="pt-6" >
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <q-card v-for="employee in data" >
                    <q-card-section>
                        <div>
                            <q-avatar size="xl">
                                <q-img
                                    :src="
                                        employee.photo ??
                                        '/img/avatar.jpg'
                                    "
                                />
                            </q-avatar>
                        </div>
                        <div>{{ employee.full_name }}</div>
                    </q-card-section>
                </q-card>
            </div>
        </div>

    </section>
</template>
<script setup>
import PageHeader from '@/components/PageHeader.vue';
import { useResourceIndex } from '@/composables/useResourceIndex';
import { onMounted } from 'vue';

const {
    data,
    fetch,
    loading,
} = useResourceIndex("employees");


const handleCreate = () => {
    console.log('Hello!')
}


onMounted(  async () => {
    await fetch();
})

</script>
