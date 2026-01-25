#!/usr/bin/env python3
"""
Script para corrigir o arquivo planilha_visualizar.php removendo blocos corrompidos
"""
import re
import os

file_path = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php'

with open(file_path, 'rb') as f:
    content = f.read()

# Converter para string UTF-8, ignorando erros
content_str = content.decode('utf-8', errors='replace')

# Remover o bloco corrompido nas linhas 293-302
# Padrão: encontrar a linha ".acao-container .btn:not" seguida de "transform: translateY(-1px);" 
# e então caracteres corrompidos até encontrar "/* Estilos para o bot"

# Abordagem: dividir em linhas e reconstruir
lines = content_str.split('\n')
new_lines = []
skip_mode = False
i = 0

while i < len(lines):
    line = lines[i]
    
    # Detectar início do bloco corrompido
    if 'transform: translateY(-1px);' in line and i + 1 < len(lines):
        next_line = lines[i + 1]
        # Verificar se a próxima linha contém caracteres corrompidos
        if '€' in next_line or 'Ã' in next_line or 'Â' in next_line:
            print(f"Detectado bloco corrompido começando na linha {i + 2}")
            new_lines.append(line)  # Manter a linha transform
            new_lines.append('        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);')
            new_lines.append('    }')
            new_lines.append('')
            
            # Pular linhas até encontrar "/* Estilos para o bot" ou ".mic-btn {"
            i += 1
            while i < len(lines):
                if '.mic-btn {' in lines[i] or '/* Estilos para o bot' in lines[i]:
                    # Encontrou o início do próximo bloco válido
                    new_lines.append('    /* Estilos para o botão de microfone */')
                    if '.mic-btn {' in lines[i]:
                        new_lines.append(lines[i])
                    break
                i += 1
            i += 1
            continue
    
    # Corrigir encoding em linhas normais
    line = line.replace('botÃ£o', 'botão')
    line = line.replace('nÃ£o', 'não')
    line = line.replace('conteÃºdo', 'conteúdo')
    line = line.replace('conteúdo', 'conteúdo')  # Já corrigido
    line = line.replace('espaÃ§o', 'espaço')
    line = line.replace('BotÃµes', 'Botões')
    line = line.replace('botÃµes', 'botões')
    line = line.replace('padrÃ£o', 'padrão')
    line = line.replace('AÃ§Ãµes', 'Ações')
    line = line.replace('aÃ§Ãµes', 'ações')
    line = line.replace('descriÃ§Ã£o', 'descrição')
    line = line.replace('observaÃ§Ã£o', 'observação')
    line = line.replace('paginaÃ§Ã£o', 'paginação')
    line = line.replace('PaginaÃ§Ã£o', 'Paginação')
    line = line.replace('temporÃ¡rio', 'temporário')
    line = line.replace('cÃ³digo', 'código')
    
    # Correções de encoding com múltiplos bytes
    line = re.sub(r'Ã¢â€Å"ÃƒÂºo', 'ão', line)
    line = re.sub(r'Ã¢â€Å"Ã¢â€â€š', 'ó', line)
    line = re.sub(r'Ã¢â€Å"Ã‚Âº', 'ã', line)
    line = re.sub(r'Ã¢â€Å"Ã¢â€¢â€˜', 'ú', line)
    line = re.sub(r'conteÃ¢â€Å"Ã¢â€¢â€˜do', 'conteúdo', line)
    line = re.sub(r'botÃ¢â€Å"ÃƒÂºo', 'botão', line)
    line = re.sub(r'nÃ¢â€Å"ÃƒÂºo', 'não', line)
    line = re.sub(r'espaÃ¢â€Å"Ã‚Âºo', 'espaço', line)
    line = re.sub(r'padrÃ¢â€Å"ÃƒÂºo', 'padrão', line)
    line = re.sub(r'PaginaÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºo', 'Paginação', line)
    
    new_lines.append(line)
    i += 1

# Juntar de volta
new_content = '\n'.join(new_lines)

# Salvar backup
backup_path = file_path + '.bak_py'
with open(backup_path, 'wb') as f:
    f.write(content)
print(f"Backup salvo em: {backup_path}")

# Salvar arquivo corrigido
with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)
print(f"Arquivo corrigido: {file_path}")

# Verificar sintaxe
import subprocess
result = subprocess.run(['php', '-l', file_path], capture_output=True, text=True)
print(f"Verificação de sintaxe:\n{result.stdout}\n{result.stderr}")
