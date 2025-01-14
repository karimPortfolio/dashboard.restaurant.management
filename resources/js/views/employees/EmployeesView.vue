<template>
    <!-- ============= // PAGE DIALOGS // ============ -->
    <CreateEmployee
        v-model:open="showCreateEmployeeDialog"
        @created="handleCreated"
    />

    <EditEmployee
        v-model:open="showEditEmployeeDialog"
        @updated="handleUpdated"
        :id="employeeToEdit?.id"
    />
    <!-- ============= // PAGE DIALOGS // ============ -->

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

        <div v-if="!loading" class="pt-16">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 gap-y-14"
            >
                <q-card v-for="employee in data" :key="employee.id">
                    <q-card-section class="relative">
                        <div
                            class="flex justify-center w-full absolute -top-7 left-0"
                        >
                            <q-avatar
                                class="ring-4 ring-gray-100 dark:ring-slate-900 w-14 h-14"
                            >
                                <q-img
                                    :src="employee.photo ?? '/img/avatar.jpg'"
                                />
                            </q-avatar>
                        </div>
                        <div class="text-center font-medium mt-6">
                            {{ employee.full_name }}
                        </div>
                        <div
                            class="text-xs text-gray-600 dark:text-gray-400 text-center mb-3"
                        >
                            {{ employee.position.label }}
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="font-medium text-sm">Email</div>
                            <div
                                class="text-end text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ employee.email ?? "N/A" }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="font-medium text-sm">Phone</div>
                            <div
                                class="text-end text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ employee.phone ?? "N/A" }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="font-medium text-sm">Salary</div>
                            <div
                                class="text-end text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ employee.converted_salary ?? "N/A" }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="font-medium text-sm">CIN</div>
                            <div
                                class="text-end text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ employee.cin_number ?? "N/A" }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="font-medium text-sm">CNSS Nº</div>
                            <div
                                class="text-end text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ employee.cnss_number ?? "N/A" }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="font-medium text-sm">Status</div>
                            <div
                                class="text-end text-sm text-gray-600 dark:text-gray-400"
                            >
                                <q-badge
                                    :color="employee.status.color"
                                    :label="employee.status.label"
                                />
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <div
                                class="font-medium text-gray-600 dark:text-gray-400 text-sm"
                            >
                                Joined {{ employee.joining_date_diff }}
                            </div>
                        </div>
                    </q-card-section>
                    <q-card-actions class="flex items-center p-0 px-4 pb-4">
                        <q-btn
                            outline
                            :color="
                                $q.dark.isActive ? 'primary-300' : 'primary'
                            "
                            label="View"
                            icon="sym_r_visibility"
                            size="sm"
                            class="grow"
                            padding="sm"
                        />
                        <q-btn
                            outline
                            :color="
                                $q.dark.isActive ? 'primary-300' : 'primary'
                            "
                            label="Edit"
                            icon="sym_r_edit"
                            size="sm"
                            class="grow"
                            padding="sm"
                            @click="handleEdit(employee)"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div v-else class="pt-16">
            <SkeletenLoading :count="6" />
        </div>

        <q-page-sticky
            v-if="!loading"
            v-scroll="handleScroll"
            position="bottom-right"
            :offset="[35, 35]"
        >
            <q-btn
                v-if="showFloatingBtn"
                @click="handleCreate"
                fab
                icon="add"
                class="bg-primary text-white dark:bg-primary-300 dark:text-slate-800"
            />
        </q-page-sticky>
    </section>
</template>
<script setup>
import PageHeader from "@/components/PageHeader.vue";
import { useResourceIndex } from "@/composables/useResourceIndex";
import { onMounted, ref, watch } from "vue";
import SkeletenLoading from "./partials/SkeletenLoading.vue";
import CreateEmployee from "./CreateEmployee.vue";
import EditEmployee from "./EditEmployee.vue";

const { data, fetch, loading } = useResourceIndex("employees");

const showFloatingBtn = ref(false);
const showCreateEmployeeDialog = ref(false);
const showEditEmployeeDialog = ref(false);

const employeeToEdit = ref({});

const handleCreate = () => {
    showCreateEmployeeDialog.value = true;
};

const handleEdit = (employee) => {
    employeeToEdit.value = employee;
    showEditEmployeeDialog.value = true;
    console.log(employeeToEdit.value);
};

const handleCreated = async () => {
    showCreateEmployeeDialog.value = false;
    await fetch();
};

const handleUpdated = async () => {
    showEditEmployeeDialog.value = false;
    await fetch();
};

function handleScroll(position) {
    showFloatingBtn.value = position > 200;
}

onMounted(async () => {
    await fetch();
});
</script>
