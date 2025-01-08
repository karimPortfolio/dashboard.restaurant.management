<template>
    <h1
        class="text-fuchsia-500 text-center mt-32"
        style="font-size: 30px; margin-top: 100px"
    >
        Login
    </h1>
    <div
        class="flex content-center"
        style="width: 100%; display: flex; justify-content: center"
    >
        <div class="q-pa-md" style="width: 600px">
            <q-form @submit="handleLogin" class="q-gutter-md">
                <q-input
                    filled
                    v-model="credentials.email"
                    label="Your Email"
                    lazy-rules
                    :error-message="messages.email?.[0]"
                    :error="'email' in messages"
                />

                <q-input
                    filled
                    type="password"
                    v-model="credentials.password"
                    label="Your Password"
                    lazy-rules
                    :error-message="messages.password?.[0]"
                    :error="'password' in messages"
                />

                <div>
                    <q-btn
                        label="Login"
                        color="purple-5"
                        @click="handleLogin"
                        :loading="loading"
                    />
                </div>
            </q-form>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref, watch } from "vue";
import { api } from "@/boot/api";
import { Cookies, useQuasar } from "quasar";
import { useAuthStore } from "@/stores/auth";
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";

const credentials = ref({});
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
            console.log(newErrorMessages);
            $q.notify({
                message: "Error",
                caption: messages.value,
                type: "negative",
            });
        }
    }
);
</script>
