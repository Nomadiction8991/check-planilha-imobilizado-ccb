# Plano de Implementação - SISTEMA 100% UPPERCASE

## Status Atual ✅
- ✅ `index.php` - Todos os textos visíveis convertidos
- ✅ `app/views/usuarios/usuarios_listar.php` - Todos os textos convertidos
- ✅ `app/helpers/uppercase_helper.php` - Helper criado
- ✅ `app/bootstrap.php` - Helper incluído
- ✅ `app/controllers/create/ProdutoCreateController.php` - Começado

## Próximas Etapas

### 1. Completar Controllers de INSERT/UPDATE
Os seguintes controllers precisam adicionar `mb_strtoupper()` ao salvar dados:
- [ ] UsuarioCreateController.php - adicionar UPPERCASE para: nome, tipo, assinatura, endereço
- [ ] UsuarioUpdateController.php - idem
- [ ] DependenciaCreateController.php/UpdateController.php - adicionar UPPERCASE para descricao
- [ ] ProdutoUpdateController.php - adicionar UPPERCASE para bem, complemento
- [ ] ProdutoPartialUpdateController.php
- [ ] ProdutoObservacaoController.php - UPPERCASE para observação

### 2. Converter Views Principais
As seguintes views precisam ter textos convertidos para UPPERCASE:
- [ ] app/views/comuns/comum_editar.php
- [ ] app/views/dependencias/dependencia_criar.php
- [ ] app/views/dependencias/dependencia_editar.php
- [ ] app/views/dependencias/dependencias_listar.php
- [ ] app/views/produtos/produto_criar.php
- [ ] app/views/produtos/produtos_listar.php
- [ ] app/views/planilhas/planilha_importar.php
- [ ] app/views/planilhas/planilha_visualizar.php
- [ ] app/views/usuarios/usuario_criar.php
- [ ] app/views/usuarios/usuario_editar.php

### 3. Dados no Banco de Dados
Para garantir que dados salvos estejam em UPPERCASE:
- Adicionar `mb_strtoupper()` em todos os controllers de INSERT/UPDATE
- Campos que devem ser UPPERCASE:
  - usuarios: nome, tipo, assinatura, nome_conjuge, assinatura_conjuge, endereço*
  - comuns: descricao, administracao, cidade, setor
  - dependencias: descricao
  - produtos: bem, complemento, tipo, marca, modelo, numero_serie, cor, especificacoes

## Estratégia de Implementação

1. **Helper UPPERCASE** (já existe)
   - Centraliza todas as funções de conversão
   
2. **Controllers** 
   - Adicionar `mb_strtoupper()` logo após `trim()` nos campos textuais
   - Usar: `$campo = mb_strtoupper(trim($_POST['campo']), 'UTF-8');`
   
3. **Views**
   - Labels, placeholders, títulos em UPPERCASE
   - Usar classes Bootstrap de texto se necessário
   
4. **Dados Existentes**
   - Considerar executar UPDATE geral no banco para converter dados antigos
   - SQL: UPDATE usuarios SET nome = UPPER(nome);

## Exemplo de Implementação

```php
// Controller - ao receber dados
$nome = mb_strtoupper(trim($_POST['nome'] ?? ''), 'UTF-8');
$descricao = mb_strtoupper(trim($_POST['descricao'] ?? ''), 'UTF-8');

// View - labels
<label for="nome" class="form-label">NOME COMPLETO <span class="text-danger">*</span></label>
```

## Prioridade
1. Controllers que fazem INSERT/UPDATE (afeta banco de dados)
2. Views mais usadas (index.php, usuários, produtos)
3. Views complementares (dependências, etc)
