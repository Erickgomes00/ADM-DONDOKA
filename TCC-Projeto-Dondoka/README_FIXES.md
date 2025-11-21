# ProjetoDondoka (corrigido)

Este pacote corrige:
- Caminho do include da conexão (agora usa `../cadastro-produtos/conexao-produtos.php`).
- Ação do formulário de produto (`salvar_produto.php` em vez de `salvar_produtos.php`).
- Remoção da dependência de tabela `genero` inexistente. Agora o gênero vem da própria `categoria`.
- Consulta de listagem ajustada para exibir `categoria` e `genero` a partir da tabela `categoria`.
- Conexão MySQL com defaults locais (`localhost`, `root`, senha vazia) e `utf8mb4`.
- Links corrigidos em `cadastro-produtos/index.php`.

## Como configurar

1. Importe o banco executando `schema.sql` no MySQL:

```sql
SOURCE schema.sql;
```

ou copie o conteúdo do arquivo e rode no seu cliente MySQL.

2. Configure as credenciais em `cadastro-produtos/conexao-produtos.php` se necessário.
   Você também pode usar variáveis de ambiente:
   - `DB_HOST` (padrão: `localhost`)
   - `DB_USER` (padrão: `root`)
   - `DB_PASSWORD` (padrão: vazio)
   - `DB_NAME` (padrão: `LojaDB`)

3. Acesse `index.php` e use os links para **Cadastrar Produto** e **Listar Produtos**.

> Observação: se você já tem tabelas diferentes do esquema proposto, ajuste os nomes das colunas no `Produto/salvar_produto.php` e `Produto/listar_produtos.php`.
