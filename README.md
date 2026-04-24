# Demo Repository

Projeto com estrutura separada em `frontend` e `backend`, com MySQL e phpMyAdmin via Docker Compose.

## Estrutura

- `frontend/`: arquivos de interface (`public`) e pasta `components`
- `backend/`: app PHP (`public`), `config`, `database` e pasta `components`
- `nginx/`: roteamento Nginx (app, `/db/` e `/front/`)

## Variaveis obrigatorias

Configure no ambiente (ex.: Coolify):

- `MYSQL_DATABASE`
- `MYSQL_USER`
- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`

Use o arquivo `.env.example` apenas como referencia de nomes.

## Configuração de Teste de Integrações n8n-deploy

- Teste 1
- Teste 2
- Teste 3
- Teste 4
- Teste 5
- Teste 6
- Teste 7
