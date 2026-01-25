#!/usr/bin/env python3
"""
Script para corrigir o arquivo planilha_visualizar.php que está com encoding corrompido.
"""
import os
import re

file_path = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php'

print(f"Lendo arquivo: {file_path}")

with open(file_path, 'rb') as f:
    content = f.read()

original_size = len(content)
print(f"Tamanho original: {original_size} bytes")

# Decodificar como latin-1 para preservar todos os bytes
text = content.decode('latin-1')

# Mapeamento de strings corrompidas para corrigidas
# Estas strings são resultado de múltiplas conversões de encoding incorretas
replacements = {
    # botão
    'botÃ¢â€Å"ÃƒÂºo': 'botão',
    'BotÃ¢â€Å"ÃƒÂºo': 'Botão',
    'botÃ¢â€Å"ÃƒÂes': 'botões',
    'BotÃ¢â€Å"ÃƒÂes': 'Botões',
    
    # código
    'cÃ¢â€Å"Ã¢â€â€šdigo': 'código',
    'CÃ¢â€Å"Ã¢â€â€šdigo': 'Código',
    'cÃ¢â€Å"Ã¢â€â€šdigos': 'códigos',
    
    # não
    'nÃ¢â€Å"ÃƒÂºo': 'não',
    'NÃ¢â€Å"ÃƒÂºo': 'Não',
    
    # conteúdo
    'conteÃ¢â€Å"Ã¢â€¢â€˜do': 'conteúdo',
    'ConteÃ¢â€Å"Ã¢â€¢â€˜do': 'Conteúdo',
    
    # temporário
    'temporÃ¢â€Å"ÃƒÂ­rio': 'temporário',
    
    # Paginação
    'PaginaÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºo': 'Paginação',
    
    # câmera
    'cÃ¢â€Å"ÃƒÂ³mera': 'câmera',
    'CÃ¢â€Å"ÃƒÂ³mera': 'Câmera',
    
    # espaço
    'espaÃ¢â€Å"Ã‚Âºo': 'espaço',
    'espaÃ¢â€Å"Ã‚Âºos': 'espaços',
    
    # versão
    'versÃ¢â€Å"ÃƒÂºo': 'versão',
    
    # Edição
    'EdiÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºo': 'Edição',
    
    # Informações
    'InformaÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂ³es': 'Informações',
    
    # contrário
    'contrÃ¢â€Å"ÃƒÂ­rio': 'contrário',
    
    # dinâmica
    'dinÃ¢â€Å"ÃƒÂ³mica': 'dinâmica',
    
    # padrão
    'padrÃ¢â€Å"ÃƒÂºo': 'padrão',
    'PadrÃ¢â€Å"ÃƒÂºo': 'Padrão',
    
    # Função
    'FunÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºo': 'Função',
    
    # traços
    'traÃ¢â€Å"Ã‚Âºos': 'traços',
    
    # disponíveis
    'disponÃ¢â€Å"Ã‚Â¡veis': 'disponíveis',
    'disponÃ¢â€Å"Ã‚Â¡vel': 'disponível',
    
    # hífen
    'hÃ¢â€Å"Ã‚Â¡fen': 'hífen',
    
    # três
    'trÃ¢â€Å"Ã‚Â¬s': 'três',
    
    # vírgula
    'vÃ¢â€Å"Ã‚Â¡rgula': 'vírgula',
    
    # vídeo
    'vÃ¢â€Å"Ã‚Â¡deo': 'vídeo',
    
    # frequência
    'frequÃ¢â€Å"Ã‚Â¬ncia': 'frequência',
    
    # localização
    'localizaÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºo': 'localização',
    
    # seleção
    'seleÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºo': 'seleção',
    
    # está
    'estÃ¢â€Å"ÃƒÂ­': 'está',
    
    # já
    'jÃ¢â€Å"ÃƒÂ­': 'já',
    
    # possível
    'possÃ¢â€Å"Ã‚Â¡vel': 'possível',
    
    # rápido
    'rÃ¢â€Å"ÃƒÂ­pido': 'rápido',
    
    # médio
    'mÃ¢â€Å"Ã‚Â®dio': 'médio',
    
    # você
    'vocÃ¢â€Å"Ã‚Â¬': 'você',
    
    # permissão
    'permissÃ¢â€Å"ÃƒÂºo': 'permissão',
    
    # Mudança
    'MudanÃ¢â€Å"Ã‚ÂºÃ¢â€Å"ÃƒÂºa': 'Mudança',
    'MUDANÃ¢â€Å"ÃƒÂ§A': 'MUDANÇA',
}

count = 0
for search, replace in replacements.items():
    found = text.count(search)
    if found > 0:
        print(f"  Encontrado '{search}' -> '{replace}' ({found}x)")
        text = text.replace(search, replace)
        count += found

print(f"\nTotal de substituições de encoding: {count}")

# Agora remover o bloco duplicado
# Vamos procurar pelo padrão específico
# Linha 139: ';â€Å"Ã¢â€Â¤// Iniciar bufferÃ¢â€Å"Ã¢â€â€šrios
# Até a linha 156 que é a duplicação

# Primeiro, vamos encontrar e mostrar as linhas problemáticas
lines = text.split('\n')
print(f"\nTotal de linhas: {len(lines)}")

# Procurar linhas com padrões corrompidos que indicam duplicação
duplicate_start = None
duplicate_end = None

for i, line in enumerate(lines):
    # Procurar pela linha corrompida que marca o início da duplicação
    if "';â€" in line or "';\xe2\x80" in line or (line.strip().startswith("';") and "Iniciar buffer" in line):
        print(f"Linha {i+1}: Possível início de bloco corrompido")
        duplicate_start = i
    
    # Verificar se há duplicação de $headerActions após uma linha estranha
    if duplicate_start is not None and "$headerActions .= '" in line and "relatorio141" in line:
        if i > duplicate_start:
            print(f"Linha {i+1}: Duplicação de $headerActions detectada")

# Padrão para remover: a linha com lixo + bloco duplicado até o ';' antes do comentário correto
# Vamos usar uma abordagem diferente: reconstruir o arquivo removendo o bloco

# Encontrar o padrão exato
pattern_text = "';"
lines_to_remove = []

for i, line in enumerate(lines):
    # A linha 139 (índice 138) deve ser apenas "';" mas tem lixo
    if i == 138:  # Linha 139
        if line != "';":
            print(f"Linha 139 está corrompida: {repr(line[:80] if len(line) > 80 else line)}")
            lines_to_remove.append(i)
    # Linhas 140-155 são o bloco duplicado
    elif 139 <= i <= 154:
        # Verificar se é parte do bloco duplicado
        if "$headerActions" in line or "relatorio141" in line or "dropdown" in line or line.strip() == "" or "logout" in line or line.strip().startswith("</") or line.strip().startswith("<"):
            lines_to_remove.append(i)
        elif line.strip().startswith("';"):
            lines_to_remove.append(i)

print(f"\nLinhas a remover: {lines_to_remove}")

# Remover as linhas identificadas
if lines_to_remove:
    new_lines = []
    for i, line in enumerate(lines):
        if i in lines_to_remove:
            continue
        new_lines.append(line)
    text = '\n'.join(new_lines)
    print(f"Removidas {len(lines_to_remove)} linhas")

# Salvar arquivo
print(f"\nSalvando arquivo...")
with open(file_path, 'wb') as f:
    f.write(text.encode('utf-8'))

new_size = len(text.encode('utf-8'))
print(f"Tamanho novo: {new_size} bytes")
print(f"Diferença: {original_size - new_size} bytes")

# Verificar sintaxe PHP
print("\nVerificando sintaxe PHP...")
import subprocess
result = subprocess.run(['php', '-l', file_path], capture_output=True, text=True)
print(result.stdout)
if result.stderr:
    print(result.stderr)

if result.returncode == 0:
    print("✅ Sintaxe PHP válida!")
else:
    print("⚠️  AVISO: O arquivo pode conter erros de sintaxe.")
