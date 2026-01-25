# Correção do arquivo planilha_visualizar.php

## Problema identificado:
O arquivo `app/views/planilhas/planilha_visualizar.php` contém um bloco corrompido nas linhas 293-451.

Este bloco contém:
- Código PHP incorretamente inserido dentro de CSS
- CSS duplicado com encoding corrompido em comentários

## Solução:

Execute os seguintes comandos no terminal:

```bash
cd /home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb

# 1. Criar backup
cp app/views/planilhas/planilha_visualizar.php app/views/planilhas/planilha_visualizar.php.backup

# 2. Criar arquivo corrigido removendo linhas 293-451
{ head -292 app/views/planilhas/planilha_visualizar.php; tail -n +452 app/views/planilhas/planilha_visualizar.php; } > app/views/planilhas/planilha_visualizar_fixed.php

# 3. Substituir arquivo original
mv app/views/planilhas/planilha_visualizar_fixed.php app/views/planilhas/planilha_visualizar.php

# 4. Verificar
wc -l app/views/planilhas/planilha_visualizar.php
# Deve mostrar aproximadamente 2063 linhas (2222 - 159 = 2063)
```

## Verificação:
Após a correção, o arquivo deve ter:
- Linhas 1-291: CSS correto
- Linha 292: Linha vazia seguida de `.acao-container .btn:not...` com box-shadow
- O bloco corrompido com `$headerActions` e `ob_start()` deve ter sido removido

## Script alternativo (se preferir):
Execute: `bash scripts/fix_planilha_corruption.sh`
