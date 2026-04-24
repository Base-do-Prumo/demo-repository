# Infra Docker e Deploy

## Stack de runtime

- Nginx
- PHP-FPM 8.3
- MySQL 8
- phpMyAdmin
- Deploy em Coolify

## Regras obrigatorias

1. Frontend e backend separados em estrutura e build.
2. Credenciais apenas por variaveis de ambiente.
3. Expor para host apenas o necessario.
4. Evitar execucao como root quando possivel.
5. Nginx deve proteger arquivos sensiveis (`.env`, `.git`, etc.).

## Nginx

- Servir frontend por rota dedicada (`/front/`) ou dominio dedicado.
- Backend deve responder em rotas/API apropriadas.
- Configurar fallback do frontend quando houver SPA router.

## Docker Compose

- Servicos com `depends_on` coerente.
- Volumes persistentes para banco.
- Nomes de variaveis padronizados com `.env.example`.

## Coolify

- Branch de producao em `main`.
- Webhook ativo para deploy automatico.
- Variaveis sensiveis definidas apenas no painel da Coolify.