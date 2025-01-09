<template>
    <div class="w-full dark:bg-slate-800 bg-white flex">
        <div class="lg:w-1/3 h-screen p-2">
            <div
                class="rounded-lg w-full h-full relative after:bg-black after:absolute after:top-0 after:left-0 after:w-full after:h-full after:rounded-lg after:opacity-40"
            >
              <!-- ======== IMAGES CAROUSEL ======= -->
               <ImagesCarousel />
              <!-- ======== IMAGES CAROUSEL ======= -->
            </div>

            <div
                class="absolute h-full w-full flex pt-3 ps-4 top-0 left-0 text-white"
            >
                <div class="p-3 pt-5 ps-4 pb-2">
                    <div
                        class="flex flex-nowrap items-center text-center md:text-start"
                    >
                        <q-avatar size="xl" class="me-2">
                            <img
                                src="https://img.freepik.com/vecteurs-libre/logo-du-restaurant_23-2148558726.jpg?t=st=1736183571~exp=1736187171~hmac=d08e909fbb8e9b00fde0fa19c2c9a3bd31e15cba0d627aa2f23f20e06c84a638&w=1800"
                            />
                        </q-avatar>
                        <span class="gt-lg inline text-2xl">
                            <span class="text-bold"> Restaurant </span>
                            Dahboard
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================/// FORM PART ///============================ -->
        <div class="q-pa-md flex-1 flex flex-col">
            <!-- ====== SETTINNGS DDROPDOWN ====== -->
            <SettingsDropdown />
            <!-- ====== SETTINNGS DDROPDOWN ====== -->

            <q-card
                class="flex flex-col justify-center items-center my-auto pb-24 shadow-none q-gutter-md"
            >
                <q-card-section class="pb-5 w-6/12 p-0">
                    <div class="text-start text-bold text-h4">Sign in</div>
                </q-card-section>
                <q-card-section class="p-0 w-6/12">
                    <q-form @submit="handleLogin">
                        <q-input
                            v-model="credentials.email"
                            label="Your Email"
                            lazy-rules
                            :error-message="messages.email?.[0]"
                            :error="'email' in messages"
                            outlined
                        />

                        <q-input
                            type="password"
                            v-model="credentials.password"
                            label="Your Password"
                            lazy-rules
                            :error-message="messages.password?.[0]"
                            :error="'password' in messages"
                            outlined
                        />

                        <!-- ====== REMEMBER & Forget Password ====== -->
                        <div class="mb-4 flex justify-between">
                            <div>
                                <q-checkbox
                                    v-model="credentials.remember"
                                    size="xs"
                                    label="Remember me"
                                    class="flex flex-nowrap text-gray-600 text-sm"
                                />
                            </div>
                            <a
                                href="/auth/forget-password"
                                class="text-sm text-primary-800"
                                >Forget password ?</a
                            >
                        </div>
                        <!-- ====== REMEMBER & Forget Password ====== -->

                        <div>
                            <q-btn
                                label="Sign in"
                                @click="handleLogin"
                                :loading="loading"
                                class="w-full dark:text-dark bg-primary text-white"
                            />
                        </div>
                    </q-form>
                </q-card-section>
            </q-card>
        </div>
    </div>
</template>
<script setup>
import { ref, watch } from "vue";
import { api } from "@/boot/api";
import { useQuasar } from "quasar";
import { useAuthStore } from "@/stores/auth";
import { useRoute } from "vue-router";
import SettingsDropdown from "./partials/SettingsDropdown.vue";
import ImagesCarousel from "./partials/ImagesCarousel.vue";

const credentials = ref({
    remember: false,
});
const loading = ref(false);

const messages = ref({});

const route = useRoute();

const $q = useQuasar();

const authStore = useAuthStore();

const handleAuth = async () => {
    try {
        const response = await api.get(import.meta.env.VITE_SANCTUM_URL);
    } catch (err) {
        $q.notify({
            message: "Error",
            caption:
                err.response.data.message ??
                "Something went wrong. Please try again.",
            type: "negative",
        });
    }
};

const handleLogin = async () => {
    try {
        await handleAuth();
        const redirectTo = route.query.redirect_to;
        await authStore.login(credentials.value, redirectTo);
    } catch (err) {
        if (err.response?.status === 422) {
            messages.value = err.response.data.errors;
        }
        $q.notify({
            message: "Error",
            caption:
                err.response?.data?.message ??
                "Something went wrong. Please try again.",
            type: "negative",
        });
    } finally {
        loading.value = false;
    }
};

watch(
    () => authStore.errorMessages,
    (newErrorMessages) => {
        if (newErrorMessages) {
            messages.value = newErrorMessages;
            // $q.notify({
            //     message: "Error",
            //     caption: messages.value,
            //     type: "negative",
            // });
        }
    }
);
</script>
