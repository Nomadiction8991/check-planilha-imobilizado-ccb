#!/usr/bin/env python3
"""
Script para corrigir encoding corrompido em planilha_visualizar.php
"""

import re
import os
from pathlib import Path

file_path = Path('/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php')

# Ler arquivo como bytes
with open(file_path, 'rb') as f:
    content_bytes = f.read()

print(f"Tamanho original: {len(content_bytes)} bytes")

# Mostrar os bytes das linhas 293-305 para debug
lines = content_bytes.split(b'\n')
print(f"\nTotal de linhas: {len(lines)}")

print("\n=== Linhas 293-305 (bytes) ===")
for i in range(292, min(310, len(lines))):
    line = lines[i]
    # Tentar decodificar, com fallback
    try:
        decoded = line.decode('utf-8')
    except:
        decoded = line.decode('latin-1')
    print(f"Linha {i+1}: {decoded[:100]}...")

# Procurar padrão do bloco corrompido
# O bloco começa com ".acao-container .btn:not" e tem "Iniciar buffer" inserido incorretamente
pattern = rb'\.acao-container \.btn:not\(\[disabled\]\):not\(\.disabled\):hover \{[^}]*?transform: translateY\(-1px\);[^}]*?\.mic-btn \{'

match = re.search(pattern, content_bytes, re.DOTALL)
if match:
    print(f"\n=== Bloco encontrado (posição {match.start()}-{match.end()}) ===")
    print(match.group()[:500])
else:
    print("\nPadrão não encontrado. Tentando outro método...")
    
    # Procurar por "Iniciar buffer" que não deveria estar no CSS
    if b'Iniciar buffer' in content_bytes:
        idx = content_bytes.find(b'Iniciar buffer')
        print(f"\n'Iniciar buffer' encontrado na posição {idx}")
        print(f"Contexto: {content_bytes[max(0,idx-100):idx+100]}")
