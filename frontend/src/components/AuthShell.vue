<template>
  <main class="shell">
    <section class="hero">
      <div>
        <h2>Base do Prumo</h2>
        <p>Painel de autenticacao com frontend em Vue.js e backend em API PHP.</p>
      </div>
      <div class="links">
        <a href="/db/" target="_blank" rel="noopener noreferrer">Banco (phpMyAdmin)</a>
      </div>
    </section>
    <section class="card">
      <slot />
    </section>
  </main>
</template>

<style scoped>
.shell {
  width: min(96vw, 920px);
  display: grid;
  grid-template-columns: 1.05fr 1fr;
  overflow: hidden;
  border-radius: 18px;
  box-shadow: 0 24px 50px rgba(0, 0, 0, 0.3);
  background: #fff;
}
.hero {
  color: #f8fafc;
  padding: 28px;
  background: linear-gradient(160deg, #1e3a8a, #0f172a 70%);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.hero h2 {
  margin: 0 0 8px;
  font-size: 1.4rem;
}
.hero p {
  margin: 0;
  line-height: 1.5;
  color: #dbeafe;
}
.links {
  margin-top: 18px;
  font-size: 0.92rem;
}
.links a {
  color: #bfdbfe;
  text-decoration: none;
}
.card {
  padding: 28px;
  color: #0f172a;
}
@media (max-width: 860px) {
  .shell {
    grid-template-columns: 1fr;
  }
  .hero {
    padding: 20px;
  }
}
</style>
