# Seguranca Obrigatoria do Projeto

## Linguagens e ambiente

- Backend: PHP 8.3
- Frontend: Vue 3 + TypeScript
- Banco: MySQL 8

## Regras obrigatorias

1. Nunca salvar segredos no repositorio (`.env`, tokens, senhas, chaves).
2. Sempre usar variaveis de ambiente para credenciais.
3. Nunca concatenar SQL; usar prepared statements/ORM seguro.
4. Senhas devem usar `password_hash()` / `password_verify()` para novas funcionalidades.
5. Validar input no backend (tipo, formato, tamanho e obrigatoriedade).
6. Proteger rotas sensiveis com autenticacao/autorizacao no backend.
7. Implementar CSRF em operacoes mutaveis (POST/PUT/PATCH/DELETE) quando usar sessao.
8. Aplicar rate limit em login e endpoints criticos.

## Frontend seguro

- Nao usar `v-html` sem sanitizacao.
- Nao guardar token sensivel em `localStorage`.
- Tratar erros sem exibir detalhes internos do servidor.

## Infra segura

- Nginx deve bloquear arquivos ocultos e adicionar headers de seguranca.
- Containers sem exposicao desnecessaria de portas.