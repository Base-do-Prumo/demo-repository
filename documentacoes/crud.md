# Padrao CRUD (Frontend + Backend separados)

## Linguagens oficiais

- Frontend: Vue 3 + TypeScript
- Backend: PHP 8.3 (API JSON)

## Regras obrigatorias

1. CRUD de tela deve ser implementado no frontend (`frontend/`).
2. CRUD de dados e regras deve ser implementado no backend (`backend/`).
3. Frontend consome API (`/api/...`) e nao acessa banco diretamente.
4. Backend valida todos os campos antes de persistir.

## UX minima do frontend

- Formulario com validacao local.
- Botao com loading e bloqueio de duplo clique.
- Lista paginada e com busca (debounce).
- Confirmacao antes de excluir.
- Estado vazio e tratamento de erro amigavel.

## Contrato minimo de API

- `GET /api/recurso`
- `GET /api/recurso/{id}`
- `POST /api/recurso`
- `PUT/PATCH /api/recurso/{id}`
- `DELETE /api/recurso/{id}`

Respostas em JSON com status HTTP coerente e mensagem clara.