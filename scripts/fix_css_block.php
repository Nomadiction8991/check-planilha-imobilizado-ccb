<?php

/**
 * Script para corrigir o bloco CSS corrompido em planilha_visualizar.php
 * Este script remove as linhas 293-305 e insere o conteúdo correto
 */

$filePath = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php';

// Ler todas as linhas
$lines = file($filePath, FILE_IGNORE_NEW_LINES);
$totalLines = count($lines);

echo "Total de linhas originais: $totalLines\n";

// Identificar as linhas problemáticas
// A linha 293 (índice 292) contém ".acao-container .btn:not([disabled]):not(.disabled):hover {"
// A linha 305 (índice 304) ou próxima contém ".mic-btn {"

$startLine = null;
$endLine = null;

for ($i = 290; $i < min(320, $totalLines); $i++) {
    $line = $lines[$i];

    // Encontrar início: ".acao-container .btn:not([disabled]):not(.disabled):hover {"
    if (strpos($line, '.acao-container .btn:not([disabled]):not(.disabled):hover') !== false) {
        $startLine = $i;
        echo "Início do bloco encontrado na linha " . ($i + 1) . "\n";
    }

    // Encontrar fim: ".mic-btn {"
    if ($startLine !== null && strpos($line, '.mic-btn {') !== false) {
        $endLine = $i;
        echo "Fim do bloco encontrado na linha " . ($i + 1) . "\n";
        break;
    }
}

if ($startLine === null || $endLine === null) {
    die("Bloco problemático não encontrado!\n");
}

// Conteúdo correto que deve substituir as linhas $startLine até $endLine
$correctBlock = [
    '    .acao-container .btn:not([disabled]):not(.disabled):hover {',
    '        transform: translateY(-1px);',
    '        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);',
    '    }',
    '',
    '    /* Estilos para o botão de microfone */',
    '    .mic-btn {',
];

// Reconstruir o arquivo
$newLines = array_merge(
    array_slice($lines, 0, $startLine),  // Linhas antes do bloco
    $correctBlock,                        // Bloco corrigido
    array_slice($lines, $endLine + 1)     // Linhas depois do bloco (pulando .mic-btn { que já está no correctBlock)
);

// Verificar que não duplicamos a linha .mic-btn
echo "Verificando próxima linha após substituição...\n";
echo "Linha atual " . ($startLine + count($correctBlock)) . ": " . ($newLines[$startLine + count($correctBlock)] ?? 'N/A') . "\n";

// Salvar
$content = implode("\n", $newLines);
file_put_contents($filePath, $content);

echo "Arquivo corrigido! Total de linhas agora: " . count($newLines) . "\n";
echo "Diferença de linhas: " . ($totalLines - count($newLines)) . "\n";
