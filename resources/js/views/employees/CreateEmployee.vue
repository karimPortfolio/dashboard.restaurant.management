<template>
    <q-dialog v-model="open">
        <q-card class="p-4 rounded-lg w-full md:max-w-2xl">
            <q-card-section class="row items-center q-pt-none q-mt-none">
                <div class="font-medium text-lg">Create Employee</div>
                <q-space />
                <q-btn icon="close" flat round dense v-close-popup />
            </q-card-section>
            <q-card-section>
                <q-form
                    id="employee-form"
                    @submit.prevent.self="handleCreate"
                    class="q-gutter-md"
                >
                    <!-- ==== FIRST NAME & LAST NAME ==== -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.first_name"
                                label="First Name"
                                :error-message="validation.first_name?.[0]"
                                :error="'first_name' in validation"
                                outlined
                                hide-bottom-space
                            />
                        </div>
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.last_name"
                                label="Last Name"
                                :error-message="validation.last_name?.[0]"
                                :error="'last_name' in validation"
                                outlined
                                hide-bottom-space
                            />
                        </div>
                    </div>

                    <!-- ==== EMAIL & PHONE ==== -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.email"
                                label="Email"
                                :error-message="validation.email?.[0]"
                                :error="'email' in validation"
                                outlined
                                hide-bottom-space
                            />
                        </div>
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.phone"
                                label="Phone"
                                :error-message="validation.phone?.[0]"
                                :error="'phone' in validation"
                                mask="#########"
                                fill-mask
                                outlined
                                hide-bottom-space
                            />
                        </div>
                    </div>

                    <!-- ==== CIN & CNSS ==== -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.cin_number"
                                label="CIN"
                                :error-message="validation.cin_number?.[0]"
                                :error="'cin_number' in validation"
                                outlined
                                hide-bottom-space
                            />
                        </div>
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.cnss_number"
                                label="CNSS"
                                :error-message="validation.cnss_number?.[0]"
                                :error="'cnss_number' in validation"
                                outlined
                                hide-bottom-space
                            />
                        </div>
                    </div>

                    <!-- ==== POSITION & SALARY ==== -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <CustomSelect
                                dense
                                v-model="newEmployee.position"
                                label="Position"
                                :error-message="validation.position?.[0]"
                                :error="'position' in validation"
                                outlined
                                resource="employee-positions"
                                option-label="label"
                                option-value="value"
                                hide-bottom-space
                                use-input
                                hint="The employee job position"
                                clearable
                            />
                        </div>
                        <div>
                            <q-input
                                dense
                                v-model="newEmployee.salary"
                                label="Salary"
                                :error-message="validation.salary?.[0]"
                                :error="'salary' in validation"
                                outlined
                                hide-bottom-space
                            >
                                <template v-slot:prepend>
                                    <q-icon name="sym_r_attach_money" />
                                </template>
                            </q-input>
                        </div>
                    </div>

                    <!-- ==== STATUS & JOINING DATE ==== -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <CustomSelect
                                dense
                                v-model="newEmployee.status"
                                label="Status"
                                :error-message="validation.status?.[0]"
                                :error="'status' in validation"
                                outlined
                                resource="employee-status"
                                option-label="label"
                                option-value="value"
                                hide-bottom-space
                                use-input
                                clearable
                            />
                        </div>
                        <div>
                            <q-input
                                type="date"
                                dense
                                v-model="newEmployee.joining_date"
                                label="Joining Date"
                                :error-message="validation.joining_date?.[0]"
                                :error="'joining_date' in validation"
                                outlined
                                hide-bottom-space
                            />
                        </div>
                    </div>
                </q-form>
            </q-card-section>
            <q-card-actions align="right" class="mt-4">
                <q-btn flat @click="handleClose" label="Cancel" />

                <q-btn
                    unelevated
                    label="Save"
                    form="employee-form"
                    type="submit"
                    icon="sym_r_save"
                    color="primary"
                    :loading="creating"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
<script setup>
import { ref } from "vue";
import { useResourceCreate } from "@/composables/useResourceCreate";
import CustomSelect from "@/components/CustomSelect.vue";

const emit = defineEmits(["created"]);
const open = defineModel("open");

const newEmployee = ref({});

const { create, creating, validation } = useResourceCreate("employees");

const handleCreate = async () => {
    await create(newEmployee.value);
    emit("created");
};

const handleClose = () => {
    newEmployee.value = {};
    validation.value = {};
    open.value = false;
};
</script>
