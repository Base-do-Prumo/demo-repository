# Documentacao Mae do Projeto

Este arquivo e a referencia principal para qualquer implementacao no projeto.

## Stack oficial (obrigatoria)

- Frontend: Vue.js (Vue 3 + TypeScript)
- Backend: PHP 8.3 (API)
- Banco de dados: MySQL 8
- Infra: Docker Compose + Nginx + Coolify
- Linguagem das documentacoes: Portugues (pt-BR)

## Regra arquitetural principal

Frontend e backend DEVEM permanecer separados.

- Frontend em `frontend/` (componentes, paginas e estado global).
- Backend em `backend/` (rotas/API, regras de negocio, acesso a banco).
- O backend expoe dados via API (JSON), e o frontend consome essa API.
- Nao usar HTML+JS puro como implementacao final de frontend.
- Evitar renderizacao de telas HTML no backend como padrao de produto.

## Documentacoes filhas obrigatorias

- `documentacoes/seguranca.md`
- `documentacoes/modularizacao.md`
- `documentacoes/infra_docker.md`
- `documentacoes/banco_de_dados.md`
- `documentacoes/crud.md`
- `documentacoes/funcoes.md`
- `documentacoes/estado_global.md`
- `documentacoes/code_review.md`

## Ordem de prioridade em caso de conflito

1. Seguranca (`seguranca.md`)
2. Integridade de dados (`banco_de_dados.md`)
3. Separacao arquitetural (`modularizacao.md`)
4. Infra e deploy (`infra_docker.md`)
5. Implementacao funcional (`crud.md`, `funcoes.md`, `estado_global.md`)
6. Revisao e estilo (`code_review.md`)

## Protocolo de implementacao

1. Classificar tarefa (frontend, backend, banco, infra, revisao).
2. Carregar esta documentacao mae.
3. Carregar as documentacoes filhas relevantes.
4. Implementar mantendo frontend e backend separados.
5. Validar impacto em seguranca, banco e deploy.
6. Resumir no fim quais docs foram usadas.

## Criterios minimos obrigatorios

- Sem segredos hardcoded.
- Validacao backend obrigatoria.
- Queries parametrizadas.
- Arquivos modulares (evitar arquivos gigantes).
- Compatibilidade com Docker/Coolify.
- Frontend separado do backend.
