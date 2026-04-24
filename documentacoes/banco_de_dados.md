## Banco de Dados (Padrao Oficial)

## Tecnologia

- Banco oficial: MySQL 8
- Scripts SQL em `backend/database/`

## Regras de modelagem

1. Toda tabela nova deve ter PK (`BIGINT AUTO_INCREMENT` ou UUID, conforme necessidade).
2. Definir FKs com `ON DELETE` explicito (`CASCADE`, `RESTRICT` ou `SET NULL`).
3. Criar indices para colunas de busca/filtro/relacionamento.
4. Campos sensiveis devem evitar remocao definitiva quando aplicavel (estrategia de soft delete).
5. Padronizar charset/collation em `utf8mb4`.

## Regras de consultas

1. Sempre parametrizar consultas.
2. Evitar N+1 (usar joins/consultas otimizadas).
3. Operacoes multi-tabela devem usar transacao.
4. Evitar `SELECT *` em consultas de listagem publica.

## Entregaveis minimos em toda alteracao de dados

- Migration SQL
- Seed SQL (quando necessario para ambiente de dev)
- Atualizacao da documentacao afetada
