# GUIA RÁPIDO - SISTEMA UPPERCASE ✅

## Resumo Executivo

O sistema foi configurado para trabalhar **100% em UPPERCASE**:
- ✅ Interface do usuário (views) com textos em MAIÚSCULAS
- ✅ Dados salvos no banco de dados em MAIÚSCULAS
- ✅ Suporte completo a UTF-8 (caracteres acentuados)

---

## Como Usar

### Ao Criar um Novo Controller com INSERT/UPDATE

**SEMPRE use `mb_strtoupper()` para campos de texto:**

```php
// ✅ CORRETO - Com UTF-8
$nome = mb_strtoupper(trim($_POST['nome'] ?? ''), 'UTF-8');
$descricao = mb_strtoupper(trim($_POST['descricao'] ?? ''), 'UTF-8');

// ❌ ERRADO - Sem UTF-8 (não funciona com acentos)
$nome = strtoupper(trim($_POST['nome'] ?? ''));

// ❌ ERRADO - Sem converter
$nome = trim($_POST['nome'] ?? '');
```

### Campos que SEMPRE devem ser UPPERCASE:

```
TABELA usuarios:
- nome
- tipo
- assinatura
- nome_conjuge
- assinatura_conjuge
- endereco_* (todos os campos de endereço)

TABELA comuns:
- descricao
- administracao
- cidade

TABELA dependencias:
- descricao

TABELA produtos:
- bem
- complemento
- descricao_completa
- observacao
```

### Ao Criar Uma Nova View

**SEMPRE use textos em MAIÚSCULAS:**

```php
<!-- ✅ CORRETO -->
<label for="nome" class="form-label">NOME COMPLETO <span class="text-danger">*</span></label>
<button type="submit" class="btn btn-primary">BUSCAR</button>
<div class="card-header"><i class="bi bi-person"></i>LISTAGEM DE USUÁRIOS</div>

<!-- ❌ ERRADO -->
<label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
<button type="submit" class="btn btn-primary">Buscar</button>
<div class="card-header"><i class="bi bi-person"></i>Listagem de Usuários</div>
```

---

## Helper Disponível

Arquivo: `app/helpers/uppercase_helper.php`

### Funções Disponíveis:

```php
// Converter um valor para uppercase
$upper = to_uppercase($value);

// Converter múltiplos campos de um array
$data = uppercase_fields($data, ['nome', 'descricao', 'cidade']);

// Obter lista de campos que devem ser uppercase
$campos = get_uppercase_fields('usuarios');  // Retorna array de campos
```

### Exemplo de Uso:

```php
<?php
require_once 'app/bootstrap.php';  // Já inclui o uppercase_helper

// Converter campos de entrada
$nome = to_uppercase(trim($_POST['nome']));
$cidade = to_uppercase(trim($_POST['cidade']));

// Ou converter múltiplos campos
$dados = [
    'nome' => $_POST['nome'],
    'descricao' => $_POST['descricao'],
    'cidade' => $_POST['cidade']
];
$dados = uppercase_fields($dados, ['nome', 'descricao', 'cidade']);
$stmt->bindValue(':nome', $dados['nome']);
$stmt->bindValue(':descricao', $dados['descricao']);
$stmt->bindValue(':cidade', $dados['cidade']);
?>
```

---

## Conversão de Dados Existentes

Se você precisa converter dados antigos no banco para UPPERCASE:

```bash
# Arquivo SQL pronto disponível em:
database/migrations/convert_to_uppercase.sql
```

**Passos:**
1. Faça backup do banco de dados
2. Execute o arquivo SQL
3. Valide os dados com as queries de conferência ao final do arquivo

---

## Checklist para Novos Desenvolvimentos

Ao criar nova funcionalidade, verificar:

- [ ] Views com textos em MAIÚSCULAS?
- [ ] Controllers com `mb_strtoupper()` nos campos de texto?
- [ ] Campos corretos sendo convertidos (ver lista acima)?
- [ ] UTF-8 sendo usado (`'UTF-8'` no parâmetro)?
- [ ] Dados sendo salvos corretamente no banco?

---

## Testando

### Testar uma View
1. Abra a página no navegador
2. Verifique se todos os textos visíveis estão em MAIÚSCULAS
3. Exemplo: labels, botões, títulos, cabeçalhos

### Testar uma Inserção/Atualização
1. Preencha um formulário com texto em minúsculas
2. Envie o formulário
3. Verifique no banco de dados se foi salvo em MAIÚSCULAS
4. SQL: `SELECT * FROM tabela WHERE id = X`

---

## Referências Rápidas

### Função mb_strtoupper()

```php
mb_strtoupper('josé da silva', 'UTF-8');  // Retorna: JOSÉ DA SILVA
mb_strtoupper('cuiabá', 'UTF-8');          // Retorna: CUIABÁ
mb_strtoupper('cônjuge', 'UTF-8');         // Retorna: CÔNJUGE
```

### Locais Onde Já Implementado

✅ Controllers:
- `create/ProdutoCreateController.php`
- `update/ProdutoUpdateController.php`
- `update/ProdutoPartialUpdateController.php`
- `update/DependenciaUpdateController.php`
- `create/ImportacaoPlanilhaController.php`

✅ Helpers:
- `helpers/comum_helper.php` (INSERT e UPDATE de comuns)

✅ Views:
- `index.php`
- `usuarios/usuarios_listar.php`

---

## Dúvidas?

Consulte os arquivos de documentação:
- `UPPERCASE-IMPLEMENTATION.md` - Implementação detalhada
- `PLANO-UPPERCASE.md` - Plano de implementação
- `uppercase_helper.php` - Código do helper

---

**Última atualização:** 16 de Dezembro de 2025
**Versão:** 1.0
**Mantido por:** Development Team
