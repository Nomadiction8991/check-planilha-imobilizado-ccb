#!/usr/bin/env python3
"""
Script para corrigir encoding corrompido em planilha_visualizar.php
Versão robusta que corrige o bloco CSS corrompido e comentários
"""

import os
import shutil
from datetime import datetime

# Caminho do arquivo
base_path = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb'
file_path = os.path.join(base_path, 'app/views/planilhas/planilha_visualizar.php')

# Criar backup
backup_path = file_path + '.backup.' + datetime.now().strftime('%Y%m%d_%H%M%S')
shutil.copy2(file_path, backup_path)

# Ler arquivo
with open(file_path, 'r', encoding='utf-8', errors='replace') as f:
    content = f.read()

original_len = len(content)

# Substituições simples de encoding corrompido
encoding_fixes = [
    # Palavras com encoding corrompido
    ('importaÃ§Ã£o', 'importação'),
    ('importaÃƒÂ§ÃƒÂ£o', 'importação'),
    ('cÃ³digo', 'código'),
    ('cÃƒÂ³digo', 'código'),
    ('conteÃºdo', 'conteúdo'),
    ('botÃ£o', 'botão'),
    ('botÃµes', 'botões'),
    ('nÃ£o', 'não'),
    ('espaÃ§o', 'espaço'),
    ('padrÃ£o', 'padrão'),
    ('contrÃ¡rio', 'contrário'),
    ('versÃ£o', 'versão'),
    ('dinÃ¢mica', 'dinâmica'),
    ('cÃ¢mera', 'câmera'),
    ('rÃ¡pido', 'rápido'),
    ('EdiÃ§Ã£o', 'Edição'),
    ('CÃ³digo', 'Código'),
    ('InformaÃ§Ãµes', 'Informações'),
    ('MUDANÃ‡A', 'MUDANÇA'),
    ('CÃ‚MERA', 'CÂMERA'),
    ('BOTÃ‚O', 'BOTÃO'),
    ('Ã‚', 'Â'),  # Cuidado com este
]

count = 0
for bad, good in encoding_fixes:
    if bad in content:
        occurrences = content.count(bad)
        content = content.replace(bad, good)
        count += occurrences

# Agora corrigir o bloco CSS corrompido (linhas 293-305)
# Este bloco tem código PHP inserido incorretamente no meio do CSS

# Padrão para identificar o bloco corrompido
corrupted_block_indicators = [
    '// Iniciar buffer',
    'headerActions',
    'ob_start();',
    '?><style>',
]

# Verificar se o bloco corrompido existe
lines = content.split('\n')
corrupted_start = None
corrupted_end = None

for i, line in enumerate(lines):
    # Procurar pelo início do bloco .acao-container .btn:hover
    if '.acao-container .btn:not([disabled]):not(.disabled):hover {' in line:
        # Verificar se as próximas linhas têm o código PHP inserido incorretamente
        for j in range(i+1, min(i+15, len(lines))):
            if any(ind in lines[j] for ind in corrupted_block_indicators):
                corrupted_start = i
                # Encontrar o fim - procurar por "/* Estilos para o" seguido de ".mic-btn"
                for k in range(j, min(j+15, len(lines))):
                    if '.mic-btn {' in lines[k]:
                        corrupted_end = k
                        break
                break
        break

if corrupted_start is not None and corrupted_end is not None:
    # Substituir o bloco corrompido pelo correto
    new_block = [
        '    .acao-container .btn:not([disabled]):not(.disabled):hover {',
        '        transform: translateY(-1px);',
        '        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);',
        '    }',
        '',
        '    /* Estilos para o botão de microfone */',
        '    .mic-btn {',
    ]
    
    # Reconstruir o arquivo
    new_lines = lines[:corrupted_start] + new_block + lines[corrupted_end+1:]
    content = '\n'.join(new_lines)
    
    # Escrever para arquivo de log
    log_msg = f"Bloco corrompido encontrado nas linhas {corrupted_start+1}-{corrupted_end+1} e corrigido.\n"
    with open(os.path.join(base_path, 'storage/logs/fix_encoding.log'), 'a') as f:
        f.write(f"[{datetime.now().isoformat()}] {log_msg}")

# Salvar arquivo corrigido
with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

new_len = len(content)

# Escrever resultado em arquivo de log
result = f"""
=== Correção de Encoding ===
Arquivo: {file_path}
Backup: {backup_path}
Substituições simples: {count}
Bloco corrompido: {'Corrigido' if corrupted_start else 'Não encontrado'}
Tamanho original: {original_len}
Tamanho novo: {new_len}
Diferença: {original_len - new_len}
"""

log_file = os.path.join(base_path, 'storage/logs/fix_encoding_result.log')
with open(log_file, 'w') as f:
    f.write(result)

print(result)
