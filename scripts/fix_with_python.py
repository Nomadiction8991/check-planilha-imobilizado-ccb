#!/usr/bin/env python3
"""
Script para identificar e corrigir o bloco corrompido em planilha_visualizar.php
"""
import os

file_path = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php'
log_path = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/storage/logs/python_fix.log'

# Ler o arquivo como bytes
with open(file_path, 'rb') as f:
    content = f.read()

lines = content.split(b'\n')
total_lines = len(lines)

with open(log_path, 'w') as log:
    log.write(f"Total lines: {total_lines}\n")
    
    # Procurar padrões
    corrupted_start = None
    corrupted_end = None
    
    for i, line in enumerate(lines):
        # Procurar a primeira ocorrência de .acao-container .btn:not seguida de headerActions nas próximas linhas
        if corrupted_start is None:
            if b'.acao-container .btn:not([disabled]):not(.disabled):hover' in line:
                # Verificar próximas linhas
                for j in range(i + 1, min(i + 15, total_lines)):
                    if b'headerActions' in lines[j] or b'ob_start()' in lines[j]:
                        corrupted_start = i
                        log.write(f"Corrupted block starts at line {i + 1}\n")
                        break
        
        # Se encontramos início, procurar o fim
        if corrupted_start is not None and corrupted_end is None:
            if i > corrupted_start + 10:
                if b'.acao-container .btn:not([disabled]):not(.disabled):hover' in line:
                    # Verificar se próximas linhas têm box-shadow
                    for j in range(i + 1, min(i + 5, total_lines)):
                        if b'box-shadow' in lines[j]:
                            corrupted_end = i - 1
                            log.write(f"Corrupted block ends at line {corrupted_end + 1}\n")
                            break
                    if corrupted_end is not None:
                        break
    
    if corrupted_start is not None and corrupted_end is not None:
        log.write(f"Removing lines {corrupted_start + 1} to {corrupted_end + 1}\n")
        
        # Criar novo conteúdo sem as linhas corrompidas
        new_lines = lines[:corrupted_start] + lines[corrupted_end + 1:]
        new_content = b'\n'.join(new_lines)
        
        # Backup
        backup_path = file_path + '.backup_python'
        with open(backup_path, 'wb') as f:
            f.write(content)
        log.write(f"Backup created: {backup_path}\n")
        
        # Salvar
        with open(file_path, 'wb') as f:
            f.write(new_content)
        
        lines_removed = corrupted_end - corrupted_start + 1
        log.write(f"SUCCESS: Removed {lines_removed} lines\n")
        log.write(f"New total: {len(new_lines)} lines\n")
        print(f"SUCCESS: Removed {lines_removed} lines ({corrupted_start + 1} to {corrupted_end + 1})")
    else:
        log.write(f"Corrupted block not found\n")
        log.write(f"corrupted_start: {corrupted_start}\n")
        log.write(f"corrupted_end: {corrupted_end}\n")
        print(f"Corrupted block not found. Check {log_path}")
