<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import AuthShell from "../components/AuthShell.vue";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const auth = useAuthStore();

const username = ref("");
const password = ref("");
const showPassword = ref(false);
const localError = ref("");

async function submitLogin(): Promise<void> {
  localError.value = "";
  if (!username.value.trim() || !password.value) {
    localError.value = "Preencha usuario e senha.";
    return;
  }

  try {
    await auth.login(username.value.trim(), password.value);
    await router.push({ name: "dashboard" });
  } catch (err) {
    localError.value = err instanceof Error ? err.message : "Erro ao fazer login.";
  }
}
</script>

<template>
  <div class="page">
    <AuthShell>
      <h1>Entrar</h1>
      <p class="subtitle">Acesse com seu usuario para continuar.</p>
      <form @submit.prevent="submitLogin">
        <label for="username">Usuario</label>
        <input id="username" v-model="username" autocomplete="username" required />

        <label for="password">Senha</label>
        <div class="password-wrap">
          <input
            id="password"
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="current-password"
            required
          />
          <button type="button" class="toggle-pass" @click="showPassword = !showPassword">
            {{ showPassword ? "Ocultar" : "Mostrar" }}
          </button>
        </div>

        <button type="submit" :disabled="auth.isLoading">
          {{ auth.isLoading ? "Entrando..." : "Acessar" }}
        </button>
      </form>

      <div v-if="localError || auth.error" class="error-box">
        {{ localError || auth.error }}
      </div>

      <p class="hint">Usuarios seed: mateusadm / 123123 e gabriel.silva / 123123</p>
    </AuthShell>
  </div>
</template>

<style scoped>
.page {
  margin: 0;
  min-height: 100vh;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #0f172a, #1d4ed8);
  padding: 16px;
}
h1 {
  margin-top: 0;
  margin-bottom: 6px;
}
.subtitle {
  margin-top: 0;
  color: #475569;
}
label {
  display: block;
  margin-top: 12px;
  font-weight: 600;
}
input {
  width: 100%;
  box-sizing: border-box;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  margin-top: 6px;
}
.password-wrap {
  position: relative;
}
.password-wrap input {
  padding-right: 88px;
}
.toggle-pass {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  border: 0;
  background: transparent;
  color: #334155;
  font-weight: 700;
  cursor: pointer;
  padding: 4px 6px;
}
button[type="submit"] {
  width: 100%;
  margin-top: 16px;
  padding: 12px;
  border: 0;
  border-radius: 8px;
  background: #2563eb;
  color: #fff;
  font-weight: 700;
  cursor: pointer;
}
button[type="submit"]:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.error-box {
  margin-top: 12px;
  padding: 10px;
  border-radius: 8px;
  color: #7f1d1d;
  background: #fee2e2;
  white-space: pre-wrap;
}
.hint {
  margin-top: 12px;
  font-size: 0.9rem;
  color: #475569;
}
</style>
