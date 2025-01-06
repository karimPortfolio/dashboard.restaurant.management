import { AuthMiddleware } from "@/middlewares/AuthMiddleware";
import router from "../router";
import { useAuthStore } from "../stores/auth";
import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true,
  withXSRFToken: true,

  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
  },
});

export default {
  install: (app) => {
    const auth = useAuthStore();

    app.config.globalProperties.$axios = api;

    // Catch 401 error and redirect to login page
    api.interceptors.response.use(
      (response) => response,
      async (error) => {

        if (error.response.status === 401) {
          // Redirect to login page
          auth.user = null;
          auth.authenticated = false;

          const middlewares = router.currentRoute.value.meta.middleware ?? [];

          if (middlewares.includes(AuthMiddleware)) {
            // Redirect to login page
            window.location.href = import.meta.env.VITE_LOGIN_URL;
          }
        }

        return Promise.reject(error);
      }
    );
  },
};

export { api };
