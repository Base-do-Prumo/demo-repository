import { computed, ref } from "vue";
import { defineStore } from "pinia";
import {
  getCsrfToken,
  getCurrentUser,
  login as apiLogin,
  logout as apiLogout,
  type AuthUser,
} from "../services/api";

export const useAuthStore = defineStore("auth", () => {
  const user = ref<AuthUser | null>(null);
  const csrfToken = ref("");
  const isLoading = ref(false);
  const error = ref("");
  const hasInitialized = ref(false);

  const isAuthenticated = computed(() => user.value !== null);

  async function initialize(): Promise<void> {
    if (hasInitialized.value) {
      return;
    }

    try {
      csrfToken.value = await getCsrfToken();
      user.value = await getCurrentUser();
    } catch {
      user.value = null;
    } finally {
      hasInitialized.value = true;
    }
  }

  async function refreshCsrf(): Promise<void> {
    csrfToken.value = await getCsrfToken();
  }

  async function login(username: string, password: string): Promise<void> {
    isLoading.value = true;
    error.value = "";

    try {
      if (!csrfToken.value) {
        await refreshCsrf();
      }
      user.value = await apiLogin({ username, password, csrfToken: csrfToken.value });
    } catch (err) {
      error.value = err instanceof Error ? err.message : "Falha ao autenticar.";
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function logout(): Promise<void> {
    if (!csrfToken.value) {
      await refreshCsrf();
    }

    await apiLogout(csrfToken.value);
    user.value = null;
    error.value = "";
    hasInitialized.value = false;
    await initialize();
  }

  return {
    user,
    csrfToken,
    isLoading,
    error,
    hasInitialized,
    isAuthenticated,
    initialize,
    refreshCsrf,
    login,
    logout,
  };
});
