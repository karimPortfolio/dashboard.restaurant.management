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
                    @submit.prevent.self="handleUpdate"
                    class="q-gutter-md"
                >
                    <!-- ====== CATEGORY PHOTO ===== -->
                    <div class="flex flex-col justify-center items-center">
                        <q-avatar class="w-20 h-20">
                            <q-img
                                :src="category.image ?? '/img/avatar.jpg'"
                            />
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
                    <!-- ====== CATEGORY PHOTO ===== -->

                    <!-- ==== CATEGORY NAME ==== -->
                    <div>
                        <q-input
                            dense
                            v-model="category.name"
                            label="Name"
                            :error-message="validation.name?.[0]"
                            :error="'name' in validation"
                            outlined
                            hide-bottom-space
                        />
                    </div>

                    <!-- ==== MAIN CATEGORY ==== -->
                    <div>
                        <custom-select
                            v-model="category.parent_category"
                            resource="categories"
                            label="Main Category"
                            :error-message="validation.parent_category?.[0]"
                            :error="'parent_category' in validation"
                            option-label="name"
                            option-value="id"
                        />
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
import { ref, watch } from "vue";
import { useResourceUpdate } from "@/composables/useResourceUpdate";
import { useResourceShow } from "@/composables/useResourceShow";
import CustomSelect from "@/components/CustomSelect.vue";
import { useFileDialog } from "@vueuse/core";

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
})

const emit = defineEmits(["created"]);
const open = defineModel("open");


const {
    data: category,
    fetch,
    loading
} = useResourceShow("categories");

const { update, updating, validation } = useResourceUpdate("categories", {
    config: {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    },
});

// only images jpeg png jpg
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

const handleUpdate = async () => {
    category.value.image = files.value ? files.value[0] : null;
    await create(category.value);
    emit("created");
};

const handleClose = () => {
    category.value = {};
    validation.value = {};
    open.value = false;
};

watch(
    () => files.value,
    (value) => {
        if (files.value && files.value.length) {
            category.value.image = URL.createObjectURL(files.value[0]);
        }
    }
);

watch(() => props.id, async (id) => {
    if (id) {
        await fetch(id);
    }
});

</script>
