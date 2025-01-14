<template>
    <q-layout
        view="lHh lpr lFf"
        container
        class="h-screen overflow-hidden bg-gray-100 dark:bg-slate-900"
    >
        <!-- ============================= -->
        <!-- ========== HEADER =========== -->
        <!-- ============================= -->

        <Header :drawer="drawer" @toggleDrawer="handleDrawerToggling" />

        <!-- ============================= -->
        <!-- ========== DRAWER =========== -->
        <!-- ============================= -->

        <q-drawer
            v-model="drawer"
            show-if-above
            :width="290"
            :breakpoint="600"
            class="bg-white dark:bg-slate-800 shadow-md dark:shadow-slate-600 z-10 h-screen flex flex-col flex-nowrap"
        >
            <div class="flex flex-nowrap items-center p-3 pt-5 ps-4 pb-2">
                <div class="text-center md:text-start">
                    <q-avatar size="lg" class="me-2">
                        <img
                            src="https://img.freepik.com/vecteurs-libre/logo-du-restaurant_23-2148558726.jpg?t=st=1736183571~exp=1736187171~hmac=d08e909fbb8e9b00fde0fa19c2c9a3bd31e15cba0d627aa2f23f20e06c84a638&w=1800"
                        />
                    </q-avatar>
                    <span class="gt-lg inline text-lg">
                        <span class="text-bold"> Restaurant </span>
                        Dahboard
                    </span>
                </div>
            </div>

            <!-- <q-separator inset /> -->

            <!-- <q-scroll-area style="height: calc(100% - 150px)" class="mt-3"> -->
            <q-list padding class="px-2 mt-4">
                <q-item
                    v-for="item in drawerItems"
                    :key="item.label"
                    class="rounded-md hover:bg-primary-50 dark:text-white text-dark dark:hover:text-primary-300 hover:text-primary-800 p-0"
                    active-class="bg-primary-50 !dark:text-primary-300 !text-primary-800"
                    clickable
                    v-ripple
                    :exact="item.exact ? true : false"
                    :to="item.route"
                >
                    <div class="w-full p-3 flex">
                        <q-item-section class="m-0 p-0 w-fit" avatar>
                            <q-icon
                                class="dark:text-primary-300 text-primary-800"
                                :name="item.icon"
                            />
                        </q-item-section>
                        <q-item-section class="m-0 p-0 w-fit">
                            {{ item.label }}
                        </q-item-section>
                    </div>
                </q-item>
            </q-list>
            <!-- </q-scroll-area> -->

            <!-- ========== PROFILE DROPDOWN =========== -->
            <ProfileDropdown class="mt-auto" :user="user" />
        </q-drawer>

        <!-- ========== DRAWER =========== -->

        <q-page-container>
            <q-page class="q-pa-md">
                <router-view />
            </q-page>
        </q-page-container>
    </q-layout>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { useAuthStore } from "../stores/auth";
import ProfileDropdown from "./partials/ProfileDropdown.vue";
import { useQuasar } from "quasar";
import Header from "./partials/Header.vue";

const drawer = ref(true);

const handleDrawerToggling = () => {
    drawer.value = !drawer.value;
};

const $q = useQuasar();

const drawerItems = [
    {
        label: "Home",
        icon: "sym_r_home",
        route: { name: "home" },
        exact: true,
    },
    {
        label: "Employees",
        icon: "sym_r_group",
        route: { name: "employees.index" },
    },
    {
        label: "Categories",
        icon: "sym_r_category",
    },
    {
        label: "Items",
        icon: "sym_r_fastfood",
    },
    {
        label: "Suppliers",
        icon: "sym_r_delivery_truck_speed",
    },
];

const authStore = useAuthStore();

const user = ref(null);

onMounted(() => {
    user.value = authStore.user.data;
});
</script>
<style scoped>
:deep(.q-field--outlined .q-field__control) {
    padding-right: 8px !important;
    border-radius: 8px !important;
}

:deep(.q-drawer-container)
{
    position: fixed !important;
}

/* .custom_btn_border
{
    border-color: blue !important;
} */
</style>
