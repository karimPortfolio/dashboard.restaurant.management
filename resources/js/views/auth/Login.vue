<template>
    <div class="w-full dark:bg-slate-800 bg-white flex">
        <div class="lg:w-1/3 bg-primary h-screen"></div>

        <div class="q-pa-md flex-1 flex flex-col ">

            <!-- ====== SETTINNGS DDROPDOWN ====== -->
             <SettingsDropdown />
            <!-- ====== SETTINNGS DDROPDOWN ====== -->

            <q-card class="flex flex-col justify-center items-center my-auto pb-24 shadow-none  q-gutter-md">
                <q-card-section class="pb-8 w-6/12">
                    <div class="text-start ms-3 text-bold text-h4">Sign in</div>
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
                                class="text-sm  text-primary-800"
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
