<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { getDashboardSummary, type DashboardSummary } from "../services/api";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const auth = useAuthStore();

const summary = ref<DashboardSummary | null>(null);
const errorMessage = ref("");
const loading = ref(true);
const logoutLoading = ref(false);

async function loadDashboard(): Promise<void> {
  loading.value = true;
  errorMessage.value = "";
  try {
    summary.value = await getDashboardSummary();
  } catch (err) {
    errorMessage.value = err instanceof Error ? err.message : "Falha ao carregar dashboard.";
  } finally {
    loading.value = false;
  }
}

async function logout(): Promise<void> {
  try {
    logoutLoading.value = true;
    await auth.logout();
    await router.push({ name: "login" });
  } catch (err) {
    errorMessage.value = err instanceof Error ? err.message : "Falha ao sair.";
  } finally {
    logoutLoading.value = false;
  }
}

onMounted(async () => {
  if (!auth.user) {
    await router.push({ name: "login" });
    return;
  }
  await loadDashboard();
});
</script>

<template>
  <main class="container">
    <section class="header">
      <h1>Dashboard</h1>
      <p>
        Bem-vindo, <strong>{{ auth.user?.username ?? "..." }}</strong>.
      </p>
      <div class="actions">
        <a class="btn btn-primary" href="/db/" target="_blank" rel="noopener noreferrer">Abrir phpMyAdmin</a>
        <button class="btn btn-danger" type="button" :disabled="logoutLoading" @click="logout">
          {{ logoutLoading ? "Saindo..." : "Sair" }}
        </button>
      </div>
    </section>

    <section class="grid">
      <article class="card">
        <h2>Usuario conectado</h2>
        <p class="value">{{ auth.user?.username ?? "-" }}</p>
      </article>
      <article class="card">
        <h2>Total de usuarios</h2>
        <p class="value">{{ summary?.totalUsers ?? 0 }}</p>
      </article>
      <article class="card">
        <h2>Banco ativo</h2>
        <p class="value">{{ summary?.database || "-" }}</p>
      </article>
    </section>

    <section class="card">
      <h2>Usuarios recentes</h2>
      <div v-if="loading" class="muted">Carregando dados...</div>
      <div v-else-if="errorMessage" class="alert">{{ errorMessage }}</div>
      <table v-else>
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Criado em</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!summary?.recentUsers?.length">
            <td colspan="2">Nenhum usuario encontrado.</td>
          </tr>
          <tr v-for="item in summary?.recentUsers ?? []" :key="`${item.username}-${item.created_at}`">
            <td>{{ item.username }}</td>
            <td>{{ item.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>
</template>

<style scoped>
.container {
  width: min(96vw, 1040px);
  margin: 24px auto;
  font-family: Arial, sans-serif;
  color: #0f172a;
}
.header {
  background: linear-gradient(120deg, #0f172a, #1d4ed8);
  color: #fff;
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 18px;
}
.header h1 {
  margin: 0 0 8px;
  font-size: clamp(1.4rem, 2vw, 1.9rem);
}
.header p {
  margin: 0;
  color: #dbeafe;
}
.actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 12px;
}
.btn {
  display: inline-block;
  padding: 10px 14px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 700;
  border: 0;
  cursor: pointer;
}
.btn-primary {
  background: #1d4ed8;
  color: #fff;
}
.btn-primary:hover {
  background: #1e40af;
}
.btn-danger {
  background: #dc2626;
  color: #fff;
}
.btn-danger:hover {
  background: #b91c1c;
}
.btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 14px;
  margin-bottom: 14px;
}
.card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 16px;
  box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
}
.card h2 {
  margin: 0 0 8px;
  font-size: 0.9rem;
  color: #475569;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.value {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 8px;
}
th, td {
  border-bottom: 1px solid #e2e8f0;
  padding: 10px 8px;
  text-align: left;
  font-size: 0.95rem;
}
th {
  color: #475569;
  font-weight: 700;
}
.alert {
  margin-top: 12px;
  background: #fee2e2;
  color: #7f1d1d;
  border-radius: 10px;
  padding: 10px 12px;
  font-weight: 600;
}
.muted {
  color: #475569;
}
@media (max-width: 860px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
