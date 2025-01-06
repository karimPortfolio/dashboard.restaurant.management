import { useAuthStore } from "@/stores/auth";

export async function RedirectAuthMiddeleware({ to, next }) {

  const authStore = useAuthStore();
  
  await authStore.fetchProfile();

  if (authStore.authenticated) {
    return next({ name: "home" });
  }

  return next();
}
