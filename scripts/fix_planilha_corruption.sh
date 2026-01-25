#!/bin/bash
# Script para corrigir o arquivo planilha_visualizar.php
# Remover linhas 293-451 (bloco corrompido)

cd /home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb

# Criar backup
cp app/views/planilhas/planilha_visualizar.php app/views/planilhas/planilha_visualizar.php.backup_$(date +%Y%m%d_%H%M%S)

# Criar novo arquivo removendo linhas 293-451
{
    head -292 app/views/planilhas/planilha_visualizar.php
    tail -n +452 app/views/planilhas/planilha_visualizar.php
} > app/views/planilhas/planilha_visualizar.php.fixed

# Substituir arquivo original
mv app/views/planilhas/planilha_visualizar.php.fixed app/views/planilhas/planilha_visualizar.php

echo "Correção aplicada!"
echo "Linhas removidas: 293-451 (159 linhas)"
echo "Backup salvo em: app/views/planilhas/planilha_visualizar.php.backup_*"

# Contar linhas do novo arquivo
wc -l app/views/planilhas/planilha_visualizar.php
