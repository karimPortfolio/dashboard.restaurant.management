<template>
    <!-- ========== DIALOGS INCLUDED ========== -->
    <CreateView v-model:open="showCreateDialog" @created="handleCreated" />

    <EditView
        v-model:open="showUpdateDialog"
        :id="itemToEdit.id"
        @updated="handleUpdated"
    />
    <!-- ========== DIALOGS INCLUDED ========== -->

    <q-page class="px-4">
        <!-- ==================== // PAGE HEADER //==================== -->
        <PageHeader
            title="Categories"
            icon="sym_r_category"
            caption="Manage your categories"
            actionIcon="sym_r_add"
            actionLabel="Add Category"
            :action="handleCreate"
        />
        <!-- ==================== // PAGE HEADER //==================== -->

        <!-- <template v-if="!loading && categories && categories.length"> -->
        <keep-alive>
            <q-infinite-scroll @load="debounceLoadChunk" :offset="200">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 pt-8"
                >
                    <q-card v-for="category in categories" :key="category.id" >
                        <q-card-section class="p-0">
                            <q-img
                                :src="category.image ?? '/img/img-placeholder.jpeg'"
                                basic
                                class="h-40"
                            />
                        </q-card-section>
                        <q-card-section
                            class="flex justify-between items-center"
                        >
                            <div>
                                <div class="text-secondary text-sm">
                                    #000{{ category.id }}
                                </div>
                                <div class="text-center font-semibold">
                                    {{ category.name }}
                                </div>
                            </div>
                            <!-- ====== ACTIONS ======= -->
                            <div>
                                <ActionsMenu
                                    :category="category"
                                    :handleUpdate="handleUpdate"
                                />
                            </div>
                            <!-- ====== ACTIONS ======= -->
                        </q-card-section>

                        <q-card-section class="pt-0">
                            <!-- ==== CREATED BY ==== -->
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-sm">
                                    Created by
                                </div>
                                <div
                                    class="text-secondary dark:text-secondary-dark text-sm"
                                >
                                    {{ category.created_by?.name ?? "N/A" }}
                                </div>
                            </div>

                            <!-- ==== CREATED AT ==== -->
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-sm">
                                    Creation Date
                                </div>
                                <div
                                    class="text-secondary dark:text-secondary-dark text-sm"
                                >
                                    {{ category.created_at }}
                                </div>
                            </div>

                            <!-- ==== PARENT CATEGORY ==== -->
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-sm">
                                    Main Category
                                </div>
                                <div
                                    class="text-secondary dark:text-secondary-dark text-sm"
                                >
                                    {{
                                        category.parent_category?.name ?? "N/A"
                                    }}
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <template v-slot:loading>
                    <div class="pt-16">
                        <SkeletenLoading :count="6" />
                    </div>
                </template>
            </q-infinite-scroll>
        </keep-alive>
        <!-- </template> -->

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
    </q-page>
</template>
<script setup>
import PageHeader from "@/components/PageHeader.vue";
import { useResourceIndex } from "@/composables/useResourceIndex";
import { onMounted, ref } from "vue";
import SkeletenLoading from "../employees/partials/SkeletenLoading.vue";
import CreateView from "./CreateView.vue";
import EditView from "./EditView.vue";
import ActionsMenu from "./includes/ActionsMenu.vue";
import { debounce } from "quasar";

const categories = ref([]);

const {
    data: categoriesChunk,
    fetch,
    loading,
    options
} = useResourceIndex("categories");

const appendCategoriesChunks = () => {
    categories.value = [...categories.value, ...categoriesChunk.value];
};

const isLoading = ref(false);

const loadMore = async (page, done) => {
    if (isLoading.value) return;
    isLoading.value = true;

    console.log('first log');

    if (
        categories.value.length === options.pagination.rowsNumber &&
        categories.value.length > 0
    ) {
        console.log('second log');
        
        done(true);
        isLoading.value = false;
        return;
    }

    console.log('third log');
    

    options.pagination.page = page;
    await fetch();

    appendCategoriesChunks();

    const result =
        options.pagination.rowsNumber / options.pagination.rowsPerPage;

    if (page > result) {
        done(true);
        isLoading.value = false;
        return;
    }

    done();
    isLoading.value = false;
};

const debounceLoadChunk = debounce(loadMore, 1000);

const showCreateDialog = ref(false);
const showFloatingBtn = ref(false);
const showUpdateDialog = ref(false);
const itemToEdit = ref({});

const handleCreate = () => {
    showCreateDialog.value = true;
};

const handleCreated = () => {
    showCreateDialog.value = false;
    fetch();
};

const handleUpdate = (item) => {
    showUpdateDialog.value = true;
    itemToEdit.value = item;
};

const handleUpdated = () => {
    showUpdateDialog.value = false;
    fetch();
};

function handleScroll(position) {
    showFloatingBtn.value = position > 200;
}

// onMounted(async () => {
//     await fetch();
// });
</script>
