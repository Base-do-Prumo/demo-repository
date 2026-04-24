# Padrao para Implementacao de Funcoes

## Linguagem por camada

- Frontend: TypeScript (Vue 3)
- Backend: PHP 8.3

## Regra principal

Toda funcao deve nascer na camada correta:

- Funcao de UI/estado: `frontend/`
- Funcao de regra de negocio, autenticacao e dados: `backend/`

## Regras tecnicas

1. Nome de funcao claro e orientado a acao.
2. Evitar funcoes longas; extrair blocos complexos.
3. Validar parametros no backend.
4. Tratar excecoes e retornar erro consistente.
5. Evitar duplicacao entre modulos.

## Saida esperada em novas features

- Funcao implementada
- Teste/manual de validacao descrito
- Atualizacao de documentacao quando a regra de negocio mudar