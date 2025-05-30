<template>
    <q-dialog v-model="open">
        <q-card class="p-4 rounded-lg w-full md:max-w-2xl">
            <q-card-section class="row items-center q-pt-none q-mt-none">
                <div class="font-medium text-lg">Edit Employee</div>
                <q-space />
                <q-btn icon="close" flat round dense v-close-popup />
            </q-card-section>
            <q-card-section>
                <q-form
                    id="employee-form"
                    @submit.prevent.self="handleUpdate"
                    class="q-gutter-md"
                >
                    <!-- ====== EMPLOYEE PHOTO ===== -->
                    <div class="flex flex-col justify-center items-center">
                        <q-avatar class="w-20 h-20">
                            <q-img :src="employee.photo ?? '/img/avatar.jpg'" />
                        </q-avatar>
                        <div>
                            <q-btn
                                flat
                                icon="sym_r_photo_camera"
                                class="ml-4 rounded-full bg-gray-500 text-white dark:bg-gray-50 dark:text-black relative bottom-8 -right-6"
                                padding="xs xs"
                                @click="openFileSelector"
                            />
                        </div>
                    </div>
                    <!-- ====== EMPLOYEE PHOTO ===== -->

                    <!-- ==== FIRST NAME & LAST NAME ==== -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <q-input
                                dense
                                v-model="employee.first_name"
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
                                v-model="employee.last_name"
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
                                v-model="employee.email"
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
                                v-model="employee.phone"
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
                                v-model="employee.cin_number"
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
                                v-model="employee.cnss_number"
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
                                v-model="employee.position"
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
                                v-model="employee.salary"
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
                                v-model="employee.status"
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
                                v-model="employee.joining_date"
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
                    :loading="updating"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { useResourceShow } from "@/composables/useResourceShow";
import { useResourceUpdate } from "@/composables/useResourceUpdate";
import CustomSelect from "@/components/CustomSelect.vue";
import { useFileDialog } from "@vueuse/core";

const props = defineProps({
    id: {
        type: Number,
    },
});

const emit = defineEmits(["updated"]);
const open = defineModel("open");

const { data: employee, fetch, loading } = useResourceShow("employees");
const { update, updating, validation } = useResourceUpdate("employees", {
    config: {
        data: {
            _method: "PUT",
        },
        method: "POST",
        headers: {
            "Content-Type": "multipart/form-data",
        },
    },
});

const {
    files,
    open: openFileSelector,
    reset,
} = useFileDialog({
    multiple: false,
    accept: {
        "image/jpeg": [".jpg", ".jpeg", ".png"],
        "application/msword": [".jpg", ".png", ".jpeg"],
    },
});

const getEmployeeImage = computed(() => {
    if (files.value && files.value.length) {
        return files.value[0];
    }

    return null;
});

const handleUpdate = async () => {
    employee.value.photo = getEmployeeImage.value;
    await update(employee.value.id, employee.value);
    resetForm();
    emit("updated");
};

const resetForm = () => {
    employee.value = {};
    validation.value = {};
    open.value = false;
    reset();
};

const handleClose = () => {
    employee.value = {};
    validation.value = {};
    open.value = false;
};

watch(
    () => files.value,
    (value) => {
        if (files.value && files.value.length) {
            employee.value.photo = URL.createObjectURL(files.value[0]);
        }
    }
);

watch(
    () => props.id,
    async (newId) => {
        if (newId) {
            await fetch(newId);
        }
    }
);
</script>
