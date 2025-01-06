<template>
    <div class="q-ma-lg">
        <h1 class="text-red-500 text-2xl">Categories</h1>
        <div v-if="loading">
            <q-spinner-dots color="primary" size="40px" />
        </div>
        <div v-if="categories && categories.length">
            <div class="text-red-500">Listed Categories</div>
            <div class="flex">
                <div v-for="product in categories" :key="product.id">
                    <q-card>
                        <q-card-section>
                            <div class="text-h6 text-4xl">{{ product.name }}</div>
                            <div class="text-subtitle2">
                                {{ product.description }}
                            </div>
                            <q-rating
                                v-model="product.rating"
                                color="orange"
                                :max="5"
                            />
                        </q-card-section>
                        <q-card-actions>
                            <q-btn flat label="Buy" color="primary" />
                            <q-btn flat label="Edit" color="primary" />
                            <q-btn flat label="Delete" color="primary" />
                        </q-card-actions>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { api } from "@/boot/api";
import { onMounted, ref } from "vue";

const categories = ref([]);
const loading = ref(false);

const fetchCategories = async () => {
    loading.value = true;
    try {
        const response = await api.get("categories");
        // console.log(response);
        if (response.status === 200) {
            categories.value = response.data;
        }
    } catch (err) {
        console.log(err);
    }
    finally {
        loading.value = false;
    }
};

onMounted(() => fetchCategories());
</script>
