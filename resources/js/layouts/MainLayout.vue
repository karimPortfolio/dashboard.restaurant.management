<template>
    <div class="">
        <q-layout
            view="lHh lpr lFf"
            container
            class="h-screen bg-gray-100 dark:bg-slate-900"
        >
            <q-header
                reveal
                :class="{
                    'bg-white text-gray-700': !$q.dark.isActive,
                    'bg-dark text-gray-300': $q.dark.isActive,
                }"
                class="shadow-sm"
            >
                <q-toolbar>
                    <q-btn
                        dense
                        flat
                        size="sm"
                        padding="sm"
                        icon="menu"
                        @click="drawer = !drawer"
                    />

                    <!-- ============== SEARCH BAR =============== -->
                    <div class="ms-5 w-1/3 py-3">
                        <q-input
                            dense
                            placeholder="Search..."
                            outlined
                        >
                            <template v-slot:prepend>
                                <q-icon name="sym_r_search" size="xs" />
                            </template>
                            <!-- <template v-slot:append>
                                <div class="flex gap-1">
                                    <q-btn
                                        outline
                                        class="h-[10px] w-[35px] bg-gray-200 dark:bg-gray-600 dark:text-white text-dark"
                                    >
                                        <q-icon
                                            name="sym_r_keyboard_command_key"
                                            class="text-base"
                                        />
                                    </q-btn>
                                    <q-btn
                                        outline
                                        class="h-[8px] w-[35px] dark:bg-gray-600 dark:text-white bg-gray-200 text-dark"
                                    >
                                        <span class="text-sm" >K</span>
                                    </q-btn>
                                </div>
                            </template> -->
                        </q-input>
                    </div>
                    <!-- ============== SEARCH BAR =============== -->

                    <q-space />

                    <!-- ============== NOTIFICATIONS =============== -->
                    <div class="mr-2">
                        <q-btn dense size="sm" padding="sm" flat>
                            <span class="relative">
                                <q-icon name="sym_r_notifications" />
                                <span
                                    class="absolute top-0 right-0 w-2 h-2 dark:bg-primary-300 bg-primary ring-2 ring-white dark:ring-slate-800 rounded-full"
                                ></span>
                            </span>
                        </q-btn>
                    </div>
                    <!-- ============== NOTIFICATIONS =============== -->
                    <div>
                        <q-btn
                            dense
                            flat
                            size="sm"
                            padding="sm"
                            icon="dark_mode"
                            @click="handleDarkToggling"
                        />
                    </div>
                </q-toolbar>
            </q-header>

            <!-- ============================= -->
            <!-- ========== DRAWER =========== -->
            <!-- ============================= -->

            <q-drawer
                v-model="drawer"
                show-if-above
                :width="290"
                :breakpoint="400"
                class="shadow-md dark:shadow-slate-600 z-10 h-screen flex flex-col"
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
                        class="rounded-md hover:bg-primary-50 dark:text-white text-dark dark:hover:text-primary-300 hover:text-primary-800 p-0"
                        active-class="bg-primary-50 !dark:text-primary-300 !text-primary-800"
                        clickable
                        v-ripple
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
                <ProfileDropdown :user="user" />
            </q-drawer>

            <!-- ========== DRAWER =========== -->

            <q-page-container>
                <q-page class="q-pa-md">
                    <router-view />
                </q-page>
            </q-page-container>
        </q-layout>
    </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { useAuthStore } from "../stores/auth";
import ProfileDropdown from "./partials/ProfileDropdown.vue";
import { useQuasar } from "quasar";

const drawer = ref(true);

const $q = useQuasar();

const drawerItems = [
    {
        label: "Home",
        icon: "sym_r_home",
        route: { name: "home" },
    },
    {
        label: "Employees",
        icon: "sym_r_group",
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

const handleDarkToggling = () => {
    $q.dark.toggle();
    localStorage.setItem("dark", $q.dark.isActive);
};

onMounted(() => {
    user.value = authStore.user.data;
});
</script>
<style scoped>
:deep(.q-field--outlined .q-field__control) {
    padding-right: 8px !important;
    border-radius: 8px !important;
}

/* .custom_btn_border
{
    border-color: blue !important;
} */
</style>
