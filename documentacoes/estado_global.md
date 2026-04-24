# Estado Global (Frontend)

## Tecnologia oficial

- Vue 3 + TypeScript
- Pinia para estado global

## Regra de separacao

Estado global existe apenas no frontend. O backend nao deve depender de store.

## Regras

1. Criar stores em `frontend/src/stores/`.
2. Usar `defineStore` no formato setup.
3. Alterar estado apenas por actions.
4. Centralizar chamadas de API nas actions ou camada de service.
5. Tratar `isLoading` e `error` no estado da store.
6. Nunca salvar token sensivel em `localStorage` sem estrategia de seguranca.