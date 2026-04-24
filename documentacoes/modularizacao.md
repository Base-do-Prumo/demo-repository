# Modularizacao do Projeto

## Stack e separacao obrigatoria

- Frontend: Vue 3 + TypeScript em `frontend/`
- Backend: PHP 8.3 em `backend/`

Nao misturar responsabilidades:

- Frontend nao implementa regra de negocio de banco.
- Backend nao deve ser usado como camada principal de renderizacao de UI.

## Regras de organizacao

1. Limite recomendado: nenhum arquivo acima de 600 linhas.
2. Um arquivo, uma responsabilidade principal.
3. Extrair logica repetida para modulos reutilizaveis.

## Frontend (Vue.js)

- Colocar componentes em `frontend/src/components/`.
- Colocar paginas/rotas em `frontend/src/pages/` (quando existir bundler app).
- Colocar chamadas HTTP em `frontend/src/services/`.
- Colocar estado global em `frontend/src/stores/`.

## Backend (PHP)

- Rotas/controladores em `backend/src/Http/` (ou equivalente definido).
- Regras de negocio em `backend/src/Services/`.
- Acesso a dados em `backend/src/Repositories/` ou camada equivalente.
- Configuracoes em `backend/config/`.
- Scripts SQL em `backend/database/`.

## Objetivo

Aumentar manutencao, escalabilidade e clareza entre time de frontend e backend.